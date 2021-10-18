@props(['feed', 'channel' => null])

@php
    $posts = $feed->posts();
@endphp

@if ($posts->isNotEmpty())
    <x-dynamic-component
        :component="'waterhole::feed-content-'.$feed->currentLayout()"
        :posts="$posts"
    />

    {{ $posts->links() }}
@else
    <div class="placeholder">
        <x-heroicon-o-chat-alt-2 class="placeholder__visual"/>
        <h3>No Posts</h3>
    </div>
@endif
