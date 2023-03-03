<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Waterhole\Forms\PostForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Notifications\NewPost;

/**
 * Controller for a post (post page, create, and update).
 *
 * Deletion is handled by the DeletePost action. Only editing of the post
 * title/body is done through this controller - updating other attributes
 * (channel, locked) is done through various Actions.
 */
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        $this->middleware('throttle:waterhole.create')->only('store');
    }

    public function show(Post $post, Request $request)
    {
        // If we've come here with reference to a particular comment, we will
        // mark any notifications about that comment as read, and then redirect
        // to the page on which that comment will be found. This allows comment
        // permalinks to be constructed without having to know the comment
        // page/index, which may be subject to change over time anyway.
        if ($commentId = $request->query('comment')) {
            $comment = $post->comments()->findOrFail($commentId);

            $request->user()?->markNotificationsRead($comment);

            return redirect($comment->post_url);
        }

        $post->load(['reactions.reactionType', 'reactions.user']);

        $comments = $post
            ->comments()
            ->with([
                'user.groups',
                'parent.user.groups',
                'reactions.reactionType',
                'reactions.user',
                'mentions',
                'attachments',
            ])
            ->oldest()
            ->paginate();

        // We already have an instance of the `post` relation for each comment,
        // since we are on the post page!
        $comments->getCollection()->each(function (Comment $comment) use ($post) {
            $comment->setRelation('post', $post);
            $comment->parent?->setRelation('post', $post);
        });

        $lastReadAt = $post->userState?->last_read_at;

        $post->userState?->read()->save();

        $request->user()?->markNotificationsRead($post);

        // Only increase the view count once per day per user/IP.
        Cache::remember(
            "view:$post->id:" .
                ($request->user() ? 'user:' . $request->user()->id : 'ip:' . $request->ip()),
            60 * 60 * 24,
            fn() => $post->increment('view_count'),
        );

        return view('waterhole::posts.show', compact('post', 'comments', 'lastReadAt'));
    }

    public function create()
    {
        $this->authorize('post.create');

        $form = new PostForm(new Post(['channel_id' => old('channel_id', request('channel_id'))]));

        return view('waterhole::posts.create', compact('form'));
    }

    public function store(Request $request)
    {
        // Only proceed with post submission if the "post" button was
        // explicitly clicked. This allows the form to be submitted for other
        // purposes, such as selecting a different channel.
        if (!$request->input('commit')) {
            return redirect()
                ->route('waterhole.posts.create', ['channel_id' => $request->input('channel_id')])
                ->withInput();
        }

        $this->authorize('post.create');

        $post = new Post([
            'user_id' => $request->user()->id,
            'channel_id' => request('channel_id'),
        ]);

        Gate::authorize('channel.post', $post->channel);

        if (!(new PostForm($post))->submit($request)) {
            return redirect()
                ->back()
                ->withInput();
        }

        // Send out a "new post" notification to all followers of this post's
        // channel, except for the user who created the post.
        Notification::send(
            $post->channel->followedBy->except($request->user()->id),
            new NewPost($post),
        );

        return redirect($post->url);
    }

    public function edit(Post $post)
    {
        $this->authorize('post.edit', $post);

        $form = new PostForm($post);

        return view('waterhole::posts.edit', compact('post', 'form'));
    }

    public function update(Post $post, Request $request)
    {
        $this->authorize('post.edit', $post);

        $post->markAsEdited();

        (new PostForm($post))->submit($request);

        return redirect($request->input('return', $post->url));
    }
}
