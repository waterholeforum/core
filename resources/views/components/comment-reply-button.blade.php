<a
    @can('post.comment', $comment->post)
        href="{{ route('waterhole.posts.comments.show', [
            'post' => $comment->post,
            'comment' => $comment->id,
        ]) }}#reply"
        data-turbo-frame="@domid($comment->post, 'comment_parent')"
    @else
        href="{{ route('waterhole.login', ['return' => $comment->post_url]) }}"
        data-turbo-frame="_top"
    @endif
    class="btn btn--small btn--transparent control"
>
    <x-waterhole::icon icon="tabler-arrow-back-up"/>
    <span>{{ __('waterhole::forum.comment-reply-button') }}</span>
</a>
