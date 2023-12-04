<a
    @can('waterhole.post.comment', $comment->post)
        href="{{ route('waterhole.posts.comments.show', [
            'post' => $comment->post,
            'comment' => $comment->id,
        ]) }}#reply"
        data-turbo-frame="@domid($comment->post, 'comment_parent')"
    @else
        href="{{ route('waterhole.login', ['return' => $comment->post_url]) }}"
        data-turbo-frame="_top"
    @endcan
    class="btn btn--sm btn--transparent"
>
    @icon('tabler-share-3', ['class' => 'flip-horizontal'])
    <span>{{ __('waterhole::forum.comment-reply-button') }}</span>
</a>
