<x-waterhole::channel-picker
    name="channel_id"
    value="{{ request('channel_id', $posts[0]->channel_id) }}"
    allow-null
/>
