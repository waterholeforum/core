<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Tonysm\TurboLaravel\Http\TurboResponseFactory;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Notifications\NewComment;
use Waterhole\View\Components\CommentFrame;
use Waterhole\View\Components\CommentFull;
use Waterhole\View\Components\Composer;
use Waterhole\View\Components\FollowButton;
use Waterhole\View\TurboStream;

use function Tonysm\TurboLaravel\dom_id;

/**
 * Controller for comments (show, create, update).
 *
 * Deletion is handled by the DeleteComment action.
 */
class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        $this->middleware('throttle:waterhole.create')->only('store');
    }

    public function show(Post $post, Comment $comment, Request $request)
    {
        // Load the comment tree for this comment, load the necessary
        // relationships, and pre-fill the `post` relationship for each comment.
        $comment = $comment->childrenAndSelf
            ->load('user.groups', 'reactions.reactionType', 'parent.user.groups')
            ->each(function ($comment) use ($post) {
                $comment->setRelation('post', $post);
                $comment->parent?->setRelation('post', $post);
            })
            ->toTree()[0];

        $request->user()?->markNotificationsRead($comment);

        $post->userState?->read()->save();

        return view('waterhole::comments.show', compact('post', 'comment'));
    }

    public function create(Post $post, Request $request)
    {
        $this->authorize('comment.create');
        $this->authorize('post.comment', $post);

        // Comments may be created in reply to a parent comment. The parent ID
        // can either be specified in a query parameter, or it may be present
        // in old POST data.
        if ($parentId = $request->get('parent', $request->old('parent_id'))) {
            $parent = $post->comments()->find($parentId);
        } else {
            $parent = null;
        }

        return view('waterhole::comments.create', compact('post', 'parent'));
    }

    public function store(Post $post, Request $request)
    {
        // Only proceed with comment submission if the "post" button was
        // explicitly clicked. This allows the form to be submitted for other
        // purposes, such as clearing the parent comment.
        if (!$request->input('commit')) {
            return redirect()
                ->route('waterhole.posts.comments.create', compact('post'))
                ->withInput();
        }

        $this->authorize('comment.create', Comment::class);
        $this->authorize('post.comment', $post);

        $data = Comment::validate($request->all());
        $data['user_id'] = $request->user()->id;

        // Validation has already ensured that the parent comment exists, but
        // we still need to make sure that it's a comment on the same post as
        // we are creating a comment on.
        if ($parentId = $data['parent_id'] ?? null) {
            $parent = Comment::find($parentId);

            abort_if(
                $parent->post_id !== $post->id,
                400,
                'Parent comment is from a different post.',
            );
        }

        $post->comments()->save($comment = new Comment($data));

        $post->userState->read()->save();

        if ($request->user()->follow_on_comment && !$post->isFollowed()) {
            $post->follow();
            $wasFollowed = true;
        }

        // Send out a "new comment" notification to all followers of this post,
        // except for the user who made the comment.
        Notification::send(
            $post->followedBy->except($request->user()->id),
            new NewComment($comment),
        );

        // If the client supports Turbo Streams, we can append the new comment
        // to the bottom of the page, and reset the comment composer. If the
        // comment has a parent, send back a fresh version of that too. And if
        // the post has been followed, refresh the post controls.
        if ($request->wantsTurboStream()) {
            $streams = [
                TurboStream::before(new CommentFrame($comment), 'bottom'),
                TurboStream::replace(new Composer($post)),
            ];

            if (isset($parent)) {
                $streams[] = TurboStream::replace(new CommentFull($parent->refresh()));
            }

            if (isset($wasFollowed)) {
                $streams[] = TurboStream::replace(new FollowButton($post));
            }

            return TurboResponseFactory::makeStream(implode($streams));
        }

        // If the comment was made in reply to another comment, then redirect
        // to the new comment on the parent comment's page. Otherwise, redirect
        // to the new comment on the post's page.
        if (isset($parent)) {
            return redirect($parent->url . '#' . dom_id($parent));
        }

        return redirect($comment->post_url);
    }

    public function edit(Post $post, Comment $comment)
    {
        $this->authorize('comment.edit', $comment);

        return view('waterhole::comments.edit', compact('comment'));
    }

    public function update(Post $post, Comment $comment, Request $request)
    {
        $this->authorize('comment.edit', $comment);

        $comment
            ->fill(Comment::validate($request->all(), $comment))
            ->markAsEdited()
            ->save();

        return redirect($comment->post_url);
    }
}
