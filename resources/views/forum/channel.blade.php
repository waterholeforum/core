<x-waterhole::layout :title="$channel->name" :data-channel="$channel->slug">
    <x-slot name="head">
        <link
            rel="alternate"
            type="application/rss+xml"
            href="{{ route('waterhole.rss.channel', compact('channel')) }}"
        />

        @unless ($channel->structure->is_listed)
            <meta name="robots" content="noindex" />
        @endunless
    </x-slot>

    <x-waterhole::index :channel="$channel">
        <x-waterhole::post-feed :feed="$feed" :channel="$channel" />
    </x-waterhole::index>
</x-waterhole::layout>
