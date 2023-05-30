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
    public function __invoke(?Post $post, Request $request)
    {
        $query = User::query();

        if ($post->exists) {
            $query
                ->joinRelationship('comments')
                ->where('comments.post_id', $post->getKey())
                ->groupBy('users.id')
                ->orderByRaw('MAX(comments.created_at) DESC');
        }

        if ($search = $request->query('q')) {
            $query
                ->where('name', 'like', "$search%")
                ->orderByRaw('name = ? desc', [$search])
                ->orderBy('name');
        } elseif (!$post->exists) {
            return [];
        }

        return $query
            ->take(5)
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
