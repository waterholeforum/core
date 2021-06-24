<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth')->only('create');
    }

    public function store(Post $post, Request $request)
    {
        $this->authorize('create', Comment::class);

        $validated = $request->validate(Comment::rules());

        if (isset($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);

            abort_if($parent->post_id !== $post->id, 400);

            // TODO: validate depth
        }

        $comment = $post->comments()->create(array_merge($validated, [
            'user_id' => Auth::id(),
        ]));

        return redirect($comment->post->url);
    }

    // public function edit(Post $post)
    // {
    //     $this->authorize('update', $post);
    //
    //     return view('waterhole::posts.edit', ['post' => $post]);
    // }
    //
    // public function update(Post $post, Request $request)
    // {
    //     $this->authorize('update', $post);
    //
    //     $validated = $request->validate(Post::rules());
    //
    //     $post->update($validated);
    //
    //     return redirect($post->url);
    // }
}
