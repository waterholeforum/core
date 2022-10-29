@if ($comment->reply_count)
    <a
        href="{{ $comment->url }}"
        class="btn btn--sm btn--outline"
        data-controller="comment-replies"
        aria-expanded="{{ $withReplies ? 'true' : 'false' }}"
        data-action="comment-replies#focusAfterLoad"
    >
        <x-waterhole::icon icon="tabler-message-circle-2"/>
        <span aria-hidden="true">{{ $comment->reply_count }}</span>
        <ui-tooltip class="visually-hidden">
            {{ __('waterhole::forum.comment-show-replies-button', ['count' => $comment->reply_count]) }}
        </ui-tooltip>
    </a>
@endif

