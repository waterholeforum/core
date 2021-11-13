<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Waterhole\Feed;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\User;
use Waterhole\PostFeed;
use Waterhole\Sorts\Latest;
use Waterhole\Sorts\Top;

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
            scope: fn($query) => $query->where('user_id', $user->id),
            sorts: ['latest', 'top'],
            defaultLayout: 'cards',
        );

        return view('waterhole::users.posts', compact('user', 'posts'));
    }

    public function comments(User $user, Request $request)
    {
        $query = $user->comments()->with([
            'post.userState',
            'post.channel',
            'user',
            'parent.user',
            'parent.post',
            'likedBy',
            'mentions',
        ]);

        $comments = new Feed(
            request: $request,
            query: $query->getQuery(),
            sorts: [
                new Latest(),
                new Top()
            ],
            defaultSort: 'latest',
        );

        return view('waterhole::users.comments', compact('user', 'comments'));
    }
}
