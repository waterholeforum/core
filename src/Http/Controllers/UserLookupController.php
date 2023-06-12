<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\View\Components\UserLabel;

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
            $users
                ->where('name', 'like', "$search%")
                ->orderByRaw('name = ? desc', [$search])
                ->orderBy('name')
                ->limit(static::LIMIT);
        }

        // If we are getting suggestions geared towards a post, we will clone
        // the above query a couple times to specifically find users who posted
        // or commented on the post.
        if ($post) {
            $comments = $users
                ->clone()
                ->selectRaw('MAX(comments.created_at) as created_at')
                ->joinRelationship(
                    'comments',
                    fn($query) => $query->where('comments.post_id', $post->getKey()),
                )
                ->groupBy('users.id')
                ->orderByRaw('MAX(comments.created_at) DESC');

            $post = $users
                ->clone()
                ->addSelect('posts.created_at')
                ->joinRelationship(
                    'posts',
                    fn($query) => $query->where('posts.id', $post->getKey()),
                );

            if ($user = $request->user()) {
                $comments->where('users.id', '!=', $user->id);
                $post->where('users.id', '!=', $user->id);
            }

            $sub = $comments->unionAll($post)->latest('created_at');

            // If there is a search query, then we still want to tack other
            // users (that haven't posted here) onto the bottom of the results.
            if ($search) {
                $sub->unionAll($users->selectRaw('NULL'));
            }
        } else {
            $sub = $users;
        }

        // Finally, select distinct users out of these unionized results, and
        // present them in the desired format.
        return User::select(['id', 'name', 'avatar'])
            ->fromSub($sub, 't')
            ->groupBy(['id', 'name', 'avatar'])
            ->take(static::LIMIT)
            ->get(['id', 'name', 'avatar'])
            ->map(
                fn(User $user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'html' => Blade::renderComponent(new UserLabel($user)),
                ],
            );
    }
}
