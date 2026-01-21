<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Waterhole\Models\Post;
use Waterhole\Models\User;

/**
 * Controller to look up users by name.
 *
 * This is used to populate the @mentions suggestion box in the text editor.
 * The return format is an array of objects with the following keys:
 *
 * - `id`: the user ID
 * - `name`: the user's name
 * - `html`: a rendering of the UserLabel component for the user
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

        if (!$search && !$post) {
            return [];
        }

        // Construct a base query that selects the data we want and filters
        // by name if a search query is present.
        $users = User::select(['users.id', 'name', 'avatar']);

        if ($search) {
            $isPgsql = (new User)->getConnection()->getDriverName() === 'pgsql';
            $operator = $isPgsql ? 'ilike' : 'like';

            $users
                ->where('name', $operator, "$search%")
                ->orderByRaw('CASE WHEN name = ? THEN 1 ELSE 0 END DESC', [$search])
                ->orderBy('name')
                ->limit(static::LIMIT);
        }

        $main = User::select(['id', 'name', 'avatar']);

        // If we are getting suggestions geared towards a post, we will clone
        // the above query a couple times to specifically find users who posted
        // or commented on the post.
        if ($post) {
            $commentsQuery = $users
                ->clone()
                ->selectRaw('MAX(comments.created_at) as created_at')
                ->selectRaw('MAX(comments.id) as comment_id')
                ->joinRelationship(
                    'comments',
                    fn($query) => $query->where('comments.post_id', $post->getKey()),
                )
                ->groupBy(['users.id', 'name', 'avatar'])
                ->orderByRaw('MAX(comments.created_at) DESC');

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
            if ($search) {
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
        return $main
            ->groupBy(['id', 'name', 'avatar'])
            ->take(static::LIMIT)
            ->get()
            ->map(
                fn(User $user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'html' => (string) view('waterhole::users.mention-suggestion', compact('user')),
                    'commentUrl' => $user->comment_id
                        ? route('waterhole.posts.comments.show', [
                            'post' => $post,
                            'comment' => $user->comment_id,
                        ])
                        : null,
                    'frameId' => $user->comment_id ? dom_id($post, 'comment_parent') : null,
                ],
            );
    }
}
