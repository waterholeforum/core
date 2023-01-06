<x-waterhole::layout :title="$channel->name" :data-channel="$channel->id">
    <x-waterhole::index :channel="$channel">
        <x-waterhole::post-feed
            :feed="$feed"
            :channel="$channel"
        />
    </x-waterhole::index>
</x-waterhole::layout>
