<x-waterhole::layout :title="$channel->name">
    <x-waterhole::index>
        <x-waterhole::feed
            :feed="$feed"
            :channel="$channel"
        />
    </x-waterhole::index>
</x-waterhole::layout>
