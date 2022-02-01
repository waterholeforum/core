<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Waterhole\Feed\CommentFeed;
use Waterhole\Feed\PostFeed;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\User;

/**
 * Controller for user profiles.
 */
class UserController extends Controller
{
    public function show(User $user)
    {
        return redirect()->route('waterhole.user.posts', compact('user'));
    }

    public function posts(User $user, Request $request)
    {
        $posts = new PostFeed(
            request: $request,
            filters: resolve_all(config('waterhole.users.post_filters', [])),
            defaultLayout: 'cards',
            scope: fn($query) => $query->whereBelongsTo($user),
        );

        return view('waterhole::users.posts', compact('user', 'posts'));
    }

    public function comments(User $user, Request $request)
    {
        $comments = new CommentFeed(
            request: $request,
            filters: resolve_all(config('waterhole.users.comment_filters', [])),
        );

        return view('waterhole::users.comments', compact('user', 'comments'));
    }
}
