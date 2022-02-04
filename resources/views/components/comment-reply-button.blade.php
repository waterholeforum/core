<a
    @can('post.comment', $comment->post)
        href="{{ route('waterhole.posts.comments.create', [
            'post' => $comment->post,
            'parent' => $comment->id
        ]) }}"
        data-turbo-frame="@domid($comment->post, 'comment_parent')"
    @else
        href="{{ route('waterhole.login', ['return' => $comment->post_url]) }}"
        data-turbo-frame="_top"
    @endif
    class="btn btn--small btn--transparent comment__control"
>
    <x-waterhole::icon icon="heroicon-o-reply"/>
    <span>Reply</span>
</a>
