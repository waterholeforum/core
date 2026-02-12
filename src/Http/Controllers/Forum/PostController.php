<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Waterhole\Extend;
use Waterhole\Forms\PostForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Controllers\Forum\Concerns\SavesPostDrafts;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\ReactionType;

/**
 * Controller for a post (post page, create, and update).
 *
 * Deletion is handled by the DeletePost action. Only editing of the post
 * title/body is done through this controller - updating other attributes
 * (channel, locked) is done through various Actions.
 */
class PostController extends Controller
{
    use SavesPostDrafts;

    public function __construct()
    {
        $this->middleware('waterhole.auth')->only('create', 'store', 'edit', 'update');
        $this->middleware(ThrottleRequests::using('waterhole.create'))->only('store');
    }

    public function show(Post $post, Request $request)
    {
        $user = $request->user();

        // If we've come here with reference to a particular comment, we will
        // mark any notifications about that comment as read, and then redirect
        // to the page on which that comment will be found. This allows comment
        // permalinks to be constructed without having to know the comment
        // page/index, which may be subject to change over time anyway.
        if ($commentId = $request->query('comment')) {
            $comment = $post->comments()->findOrFail($commentId);

            $user?->markNotificationsRead($comment);

            return redirect($comment->post_url);
        }

        $query = $post->comments()->getQuery();

        $extender = resolve(Extend\Query\CommentQuery::class);

        foreach ([...$extender->values(), ...$extender->thread->values()] as $scope) {
            $scope($query, $post);
        }

        $comments = $query->oldest()->paginate();

        // We already have an instance of the `post` relation for each comment,
        // since we are on the post page!
        $comments->getCollection()->each(function (Comment $comment) use ($post) {
            $comment->setRelation('post', $post);
            $comment->parent?->setRelation('post', $post);
        });

        $lastReadAt = $post->userState?->last_read_at;

        $post->userState?->read()->save();

        $user?->markNotificationsRead($post);

        // Only increase the view count once per day per user/IP.
        Cache::remember(
            "view:$post->id:" . ($user ? "user:$user->id" : 'ip:' . $request->ip()),
            60 * 60 * 24,
            fn() => $post->increment('view_count'),
        );

        $headings = $post->bodyHeadings();

        return view('waterhole::posts.show', compact('post', 'comments', 'lastReadAt', 'headings'));
    }

    public function create()
    {
        $this->authorize('waterhole.post.create');

        $draft = request()->user()?->drafts()->first();

        if ($draft?->payload && !session()->hasOldInput()) {
            session()->flashInput($draft->payload);
        }

        $form = new PostForm(new Post(['channel_id' => old('channel_id', request('channel_id'))]));

        return view('waterhole::posts.create', compact('form', 'draft'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Only proceed with post submission if the "post" button was
        // explicitly clicked. This allows the form to be submitted for other
        // purposes, such as selecting a different channel.
        if (!$request->input('commit')) {
            $this->savePostDraft($user, $request->all());

            return redirect()
                ->route('waterhole.posts.create', ['channel_id' => $request->input('channel_id')])
                ->withInput();
        }

        $this->authorize('waterhole.post.create');

        $post = new Post([
            'user_id' => $user->id,
            'channel_id' => request('channel_id'),
        ]);

        Gate::authorize('waterhole.channel.post', $post->channel);

        $post->is_approved =
            $user->can('waterhole.channel.moderate', $post->channel) ||
            (!$user->requiresApproval() && !$post->channel->require_approval_posts);

        if (!(new PostForm($post))->submit($request)) {
            return redirect()->back()->withInput();
        }

        if ($user->follow_on_comment) {
            $post->follow();
        }

        $user->drafts()->delete();

        return redirect($post->url);
    }

    public function edit(Post $post)
    {
        $this->authorize('waterhole.post.edit', $post);

        $form = new PostForm($post);

        return view('waterhole::posts.edit', compact('post', 'form'));
    }

    public function update(Post $post, Request $request)
    {
        $this->authorize('waterhole.post.edit', $post);

        $post->markAsEdited();

        (new PostForm($post))->submit($request);

        return redirect($request->input('return', $post->url));
    }

    public function reactions(Post $post, ReactionType $reactionType)
    {
        return view('waterhole::reactions.list', [
            'model' => $post,
            'reactionType' => $reactionType,
        ]);
    }
}
