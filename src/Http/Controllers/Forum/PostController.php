<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
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

        $post->load('likedBy');

        $comments = $post
            ->comments()
            ->with(['user.groups', 'parent.user.groups', 'likedBy', 'mentions'])
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

        return view('waterhole::posts.show', compact('post', 'comments', 'lastReadAt'));
    }

    public function create(Request $request)
    {
        $this->authorize('post.create');

        if ($channelId = $request->query('channel')) {
            $channel = Channel::findOrFail($channelId);
        } else {
            $channel = null;
        }

        return view('waterhole::posts.create', compact('channel'));
    }

    public function store(Request $request)
    {
        // Only proceed with post submission if the "post" button was
        // explicitly clicked. This allows the form to be submitted for other
        // purposes, such as selecting a different channel.
        if (!$request->input('commit')) {
            return redirect()
                ->route('waterhole.posts.create', ['channel' => $request->input('channel_id')])
                ->withInput();
        }

        $this->authorize('post.create');

        $data = Post::validate($request->all());
        $data['user_id'] = $request->user()->id;

        $this->authorize('channel.post', Channel::findOrFail($data['channel_id']));

        $post = Post::create($data);

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

        return view('waterhole::posts.edit', compact('post'));
    }

    public function update(Post $post, Request $request)
    {
        $this->authorize('post.edit', $post);

        $post
            ->fill(Post::validate($request->all(), $post))
            ->markAsEdited()
            ->save();

        return redirect($request->input('return', $post->url));
    }
}
