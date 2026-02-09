<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Waterhole\Models\Enums\Mentionable;
use Waterhole\Models\Group;
use Waterhole\Models\Post;
use Waterhole\Models\User;

/**
 * Controller to look up users by name.
 *
 * This is used to populate the @mentions suggestion box in the text editor.
 * The return format is an array of objects with the following keys:
 *
 * - `id`: the mentionable ID
 * - `name`: the mentionable name
 * - `html`: a rendering of the suggestion row
 * - `value`: the text to insert into the editor
 */
class UserLookupController extends Controller
{
    const LIMIT = 5;

    public function __invoke(?Post $post, Request $request)
    {
        if (!$post->exists) {
            $post = null;
        }

        $search = $request->query('q');
        $groupSearch = $search;
        $searchUsers = $search;

        if ($search && Str::startsWith(Str::lower($search), 'group:')) {
            $groupSearch = trim(Str::substr($search, strlen('group:')));
            $searchUsers = null;
        }

        if (!$search && !$post) {
            return [];
        }

        $user = $request->user();

        $userResults = collect();

        if ($searchUsers !== null) {
            // Construct a base query that selects the data we want and filters
            // by name if a search query is present.
            $users = User::select(['users.id', 'name', 'avatar']);

            if ($searchUsers) {
                $operator =
                    (new User())->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

                $users
                    ->where('name', $operator, "$searchUsers%")
                    ->orderByRaw("CASE WHEN name $operator ? THEN 1 ELSE 0 END DESC", [
                        $searchUsers,
                    ])
                    ->orderBy('name')
                    ->limit(static::LIMIT);
            }

            $main = User::select(['id', 'name', 'avatar']);

            // If we are getting suggestions geared towards a post, we will clone
            // the above query a couple times to specifically find users who posted
            // or commented on the post.
            if ($post) {
                $commentsCreatedAt = $users->getGrammar()->wrap('comments.created_at');
                $commentsId = $users->getGrammar()->wrap('comments.id');

                $commentsQuery = $users
                    ->clone()
                    ->selectRaw("MAX($commentsCreatedAt) as created_at")
                    ->selectRaw("MAX($commentsId) as comment_id")
                    ->joinRelationship(
                        'comments',
                        fn($query) => $query->where('comments.post_id', $post->getKey()),
                    )
                    ->groupBy(['users.id', 'name', 'avatar'])
                    ->orderByRaw("MAX($commentsCreatedAt) DESC");

                $postQuery = $users
                    ->clone()
                    ->addSelect('posts.created_at')
                    ->selectRaw('NULL as comment_id')
                    ->joinRelationship(
                        'posts',
                        fn($query) => $query->where('posts.id', $post->getKey()),
                    );

                if ($user = $request->user()) {
                    $commentsQuery->where('users.id', '!=', $user->id);
                    $postQuery->where('users.id', '!=', $user->id);
                }

                $sub = $commentsQuery->unionAll($postQuery)->latest('created_at');

                // If there is a search query, then we still want to tack other
                // users (that haven't posted here) onto the bottom of the results.
                if ($searchUsers) {
                    $sub->unionAll(
                        $users->selectRaw('NULL as created_at')->selectRaw('NULL as comment_id'),
                    );
                }

                $main->fromSub($sub, 'a')->selectRaw('MAX(comment_id) as comment_id');
            } else {
                $main->fromSub($users, 'a');
            }

            // Finally, select distinct users out of these unionized results, and
            // present them in the desired format.
            $userResults = $main
                ->groupBy(['id', 'name', 'avatar'])
                ->take(static::LIMIT)
                ->get()
                ->map(function (User $user) use ($post): array {
                    $commentId = $post ? $user->comment_id : null;

                    return [
                        'id' => $user->id,
                        'type' => 'user',
                        'name' => $user->name,
                        'value' => $user->name,
                        'html' => (string) view(
                            'waterhole::users.mention-suggestion',
                            compact('user', 'commentId'),
                        ),
                        'commentUrl' => $commentId
                            ? route('waterhole.posts.comments.show', [
                                'post' => $post,
                                'comment' => $commentId,
                            ])
                            : null,
                        'frameId' => $commentId ? dom_id($post, 'comment_parent') : null,
                    ];
                });
        }

        $groups = Group::query()->where('is_public', true);

        if ($groupSearch) {
            $operator =
                (new Group())->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

            $groups
                ->where('name', $operator, "$groupSearch%")
                ->orderByRaw("CASE WHEN name $operator ? THEN 1 ELSE 0 END DESC", [$groupSearch]);
        }

        $groupResults = collect();

        if ($user) {
            if (!$user->isAdmin()) {
                $isModerator =
                    $post?->channel && $user->can('waterhole.channel.moderate', $post->channel);

                $groups->where(function ($query) use ($user, $isModerator) {
                    $query->where('mentionable', Mentionable::Anyone->value);

                    if ($isModerator) {
                        $query
                            ->orWhere('mentionable', Mentionable::Moderators->value)
                            ->orWhere('mentionable', Mentionable::Members->value)
                            ->orWhereNull('mentionable');

                        return;
                    }

                    $query->orWhere(function ($query) use ($user) {
                        $query
                            ->where(function ($query) {
                                $query
                                    ->where('mentionable', Mentionable::Members->value)
                                    ->orWhereNull('mentionable');
                            })
                            ->whereRelation('users', 'users.id', $user->id);
                    });
                });
            }

            $groupResults = $groups->orderBy('name')->limit(static::LIMIT)->get();
        }

        if ($groupResults->isNotEmpty()) {
            $usersCounts = DB::table('group_user')
                ->selectRaw('group_id, COUNT(*) as users_count')
                ->whereIn('group_id', $groupResults->modelKeys())
                ->groupBy('group_id')
                ->pluck('users_count', 'group_id');

            $groupResults->each(
                fn(Group $group) => $group->setAttribute(
                    'users_count',
                    (int) ($usersCounts[$group->id] ?? 0),
                ),
            );
        }

        $groupResults = $groupResults->map(
            fn(Group $group) => [
                'id' => $group->id,
                'type' => 'group',
                'name' => $group->name,
                'value' => 'group:' . $group->name,
                'html' => (string) view('waterhole::users.mention-suggestion', compact('group')),
            ],
        );

        if ($searchUsers === null) {
            return $groupResults->take(static::LIMIT)->values();
        }

        return $groupResults->concat($userResults)->values();
    }
}
