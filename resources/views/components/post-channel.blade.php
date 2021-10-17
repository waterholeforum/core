@props(['post'])

<span>
    <x-waterhole::channel-label :channel="$post->channel" link/>
</span>
