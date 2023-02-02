<div class="stack gap-lg">
    <h1 class="h4">
        {{ __('waterhole::forum.move-post-title', ['count' => $posts->count()]) }}
        @if ($posts->count() === 1)
            {{ $posts[0]->title }}
        @endif
    </h1>

    <x-waterhole::channel-picker
        name="channel_id"
        value="{{ request('channel_id', $posts[0]->channel_id) }}"
    />
</div>
