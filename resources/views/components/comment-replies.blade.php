@props(['comment', 'withReplies'])

@if ($comment->reply_count)
    <a
        href="{{ $comment->url }}"
        class="btn btn--small btn--outline"
        data-controller="comment-replies"
        aria-expanded="{{ $withReplies ? 'true' : 'false' }}"
        aria-controls="@domid($comment, 'replies')"
    >
        <x-waterhole::icon icon="waterhole-o-comment"/>
        <span>{{ $comment->reply_count }}</span>
        <ui-tooltip class="visually-hidden">Show {{ $comment->reply_count }} replies</ui-tooltip>
    </a>
@endif
