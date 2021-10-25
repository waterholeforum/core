{{--@props(['post'])--}}

{{--<span class="metric metric--comments metric--{{ $post->comment_count }}">--}}
{{--    <x-heroicon-o-chat class="icon"/>--}}
{{--    <span>--}}
{{--        <span class="comment-count">{{ $post->comment_count }}</span>--}}
{{--        <span class="metric__label">comments</span>--}}
{{--    </span>--}}
{{--</span>--}}

{{--@props(['comment', 'withReplies'])--}}

@if ($post->comment_count)
    <a
        href="{{ $post->url }}#comments"
        class="btn btn--small btn--outline"
        data-action="post#appearAsRead"
    >
        <x-waterhole::icon icon="waterhole-o-comment"/>
        <span>{{ $post->comment_count }}</span>
        <ui-tooltip class="visually-hidden">{{ $post->comment_count }} comments</ui-tooltip>
    </a>
@endif
