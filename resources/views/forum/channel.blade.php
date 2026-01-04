<x-waterhole::layout
    :title="$channel->name"
    :data-channel="$channel->slug"
    :seo="[
        'description' => $channel->description_html,
        'url' => route('waterhole.channels.show', compact('channel')),
        'noindex' => !$channel->structure->is_listed,
        'schema' => ['@type' => 'CollectionPage'],
    ]"
>
    <x-slot name="head">
        <link
            rel="alternate"
            type="application/rss+xml"
            href="{{ route('waterhole.rss.channel', compact('channel')) }}"
        />
    </x-slot>

    <x-waterhole::index :channel="$channel">
        <h1 class="visually-hidden">{{ $channel->name }}</h1>

        <x-waterhole::post-feed :feed="$feed" :channel="$channel" />
    </x-waterhole::index>
</x-waterhole::layout>
