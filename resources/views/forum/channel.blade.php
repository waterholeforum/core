<x-waterhole::forum-layout
    :title="$channel->name"
    :active-node="$channel->structure"
    :data-channel="$channel->slug"
    :rss="route('waterhole.rss.channel', compact('channel'))"
    show-sidebar
    :seo="[
        'description' => $channel->description_text,
        'url' => $channel->url,
        'noindex' => ! $channel->isListed(),
        'schema' => ['@type' => 'CollectionPage'],
    ]"
>
    <x-waterhole::index :active-node="$channel->structure">
        <x-waterhole::post-feed :feed="$feed" :channel="$channel" />
    </x-waterhole::index>
</x-waterhole::forum-layout>
