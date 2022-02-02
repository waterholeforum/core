@if ($post->comment_count)
    <a
        href="{{ $post->url }}#comments"
        class="btn btn--small btn--outline"
        data-action="post#appearAsRead"
    >
        <x-waterhole::icon icon="waterhole-o-comment"/>
        <span>{{ compact_number($post->comment_count) }}</span>
        <ui-tooltip class="visually-hidden">{{ $post->comment_count }} comments</ui-tooltip>
    </a>
@endif
