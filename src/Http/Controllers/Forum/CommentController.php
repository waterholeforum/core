<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Tonysm\TurboLaravel\Http\TurboResponseFactory;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Views\Components\CommentFrame;
use Waterhole\Views\Components\CommentFull;
use Waterhole\Views\Components\Composer;
use Waterhole\Views\TurboStream;

use function Tonysm\TurboLaravel\dom_id;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth')->except('show');
        $this->middleware('waterhole.throttle:waterhole.create')->only('store', 'update');
    }

    public function show(Post $post, Comment $comment)
    {
        $all = $comment->childrenAndSelf
            ->load('user', 'likedBy', 'parent.post', 'parent.user')
            ->each->setRelation('post', $post);

        $comment = $all->toTree()[0];

        return view('waterhole::comments.show', compact('post', 'comment'));
    }

    public function create(Post $post, Request $request)
    {
        $this->authorize('create', Comment::class);
        $this->authorize('reply', $post);

        $parent = null;

        if ($parentId = $request->get('parent', $request->old('parent_id'))) {
            $parent = $post->comments()->find($parentId);
        }

        return view('waterhole::comments.create', compact('post', 'parent'));
    }

    public function store(Post $post, Request $request)
    {
        if (! $request->has('commit')) {
            return redirect()->route('waterhole.posts.comments.create', compact('post'))->withInput();
        }

        $this->authorize('create', Comment::class);
        $this->authorize('reply', $post);

        $data = $request->validate(Comment::rules(), Comment::messages());

        if ($parentId = $data['parent_id'] ?? null) {
            $parent = Comment::find($parentId);

            abort_if($parent->post_id !== $post->id, 400);
        }

        $comment = Comment::byUser($request->user(), $data);

        $post->comments()->save($comment);
        $post->userState?->read()->save();

        if ($request->wantsTurboStream()) {
            $streams = [
                TurboStream::before(new CommentFrame($comment), 'bottom'),
                TurboStream::replace((new Composer($post))->withAttributes(['class' => 'can-sticky'])),
            ];

            if (isset($parent)) {
                $streams[] = TurboStream::replace(new CommentFull($parent->fresh()));
            }

            return TurboResponseFactory::makeStream(implode($streams));
        }

        if (isset($parent)) {
            return redirect($parent->url.'#comment-'.$parent->id);
        }

        return redirect($post->url.'?page='.ceil($post->comment_count / $comment->getPerPage()).'#comment-'.$comment->id);
    }

    public function edit(Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('waterhole::comments.edit', ['comment' => $comment]);
    }

    public function update(Post $post, Comment $comment, Request $request)
    {
        $this->authorize('update', $comment);

        $data = $request->validate(Comment::rules());

        $comment->update($data);

        return redirect($request->get('return', $comment->post_url));
    }
}
