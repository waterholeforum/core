<x-waterhole::layout :title="$channel->name">
    <x-waterhole::index>
        <x-waterhole::post-feed
            :feed="$feed"
            :channel="$channel"
        />
    </x-waterhole::index>
</x-waterhole::layout>
