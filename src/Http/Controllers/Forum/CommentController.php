<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Waterhole\Extend\CommentsSort;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Sorts\Oldest;
use Waterhole\Sorts\Sort;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth')->except('show');
        $this->middleware('waterhole.throttle:waterhole.create')->only('store', 'update');
    }

    // public function show(Comment $comment, Request $request)
    // {
    //     return redirect(
    //         $comment->post->url.'?'.http_build_query([
    //             'sort' => $request->query('sort'),
    //             'comment' => $comment->id,
    //         ]).'#comment-'.$comment->id
    //     );
    // }

    public function create(Post $post, Request $request)
    {
        $this->authorize('create', Comment::class);
        $this->authorize('reply', $post);

        $parent = null;

        if ($parentId = $request->get('parent')) {
            $parent = $post->comments()->find($parentId);
        }

        return view('waterhole::comments.create', compact('post', 'parent'));
    }

    public function store(Post $post, Request $request)
    {
        $this->authorize('create', Comment::class);
        $this->authorize('reply', $post);

        $data = $request->validate(Comment::rules());

        if (isset($data['parent_id'])) {
            $parent = Comment::find($data['parent_id']);


            abort_if($parent->post_id !== $post->id, 400);
            // abort_if($parent->ancestors()->count() === config('waterhole.forum.comment_depth', 1), 403);
        }

        $comment = Comment::byUser($request->user(), $data);

        $post->comments()->save($comment);

        if (isset($parent)) {
            return redirect($parent->url.'#comment-'.$parent->id);
        }

        return redirect($post->url.'?page='.ceil($post->comment_count / $comment->getPerPage()).'#comment-'.$comment->id);
    }

    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('waterhole::comments.edit', ['comment' => $comment]);
    }

    public function update(Comment $comment, Request $request)
    {
        $this->authorize('update', $comment);

        $data = $request->validate(Comment::rules());

        $comment->update($data);

        return redirect($comment->url);
    }
}
