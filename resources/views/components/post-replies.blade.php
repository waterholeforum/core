@props(['post'])

<span class="metric metric--comments metric--{{ $post->comment_count }}">
    <x-heroicon-o-chat class="icon"/>
    <span>
        <span class="comment-count">{{ $post->comment_count }}</span>
        <span class="metric__label">comments</span>
    </span>
</span>
