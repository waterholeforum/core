@props(['comment'])

@can('reply', $comment->post)
    <a
        href="{{ route('waterhole.posts.comments.create', [
            'post' => $comment->post,
            'parent' => $comment->id
        ]) }}"
        class="btn btn--small btn--transparent comment__control"
        data-turbo-frame="@domid($comment->post, 'comment_parent')"
    >
        <x-waterhole::icon icon="heroicon-o-reply"/>
        <span>Reply</span>
    </a>
@endcan
