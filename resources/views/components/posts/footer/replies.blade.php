@props(['post'])

<span class="metric metric--comments metric--{{ $post->comment_count }}">
    <x-heroicon-o-chat class="icon"/>
    {{ $post->comment_count }}
</span>
