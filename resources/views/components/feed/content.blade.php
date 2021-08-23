@props(['feed', 'channel' => null])

@php
    $posts = $feed->posts();
@endphp

@if ($posts->isNotEmpty())
    <x-dynamic-component
        :component="'waterhole::feed.content-'.$feed->currentLayout()"
        :posts="$posts"
    />

    {{ $posts->links() }}
@else
    <div class="placeholder">
        <x-heroicon-o-chat-alt-2 class="placeholder__visual"/>
        <h3>There's nothing here.</h3>
        <p>You can <a href="{{ route('waterhole.posts.create') }}?channel={{ $channel->id }}">post something new</a>.</p>
    </div>
@endif
