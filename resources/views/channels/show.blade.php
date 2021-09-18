<x-waterhole::layout :title="$channel->name">
    <x-waterhole::index>

        @components(Waterhole\Extend\FeedHeader::getComponents(), compact('feed', 'channel'))

        <x-waterhole::feed-content :feed="$feed" :channel="$channel"/>

    </x-waterhole::index>
</x-waterhole::layout>
