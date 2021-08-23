@props(['post'])

<span class="metric metric--score metric--{{ $post->score }}">
    <x-heroicon-o-thumb-up class="icon"/>
    {{ $post->score }}
</span>
