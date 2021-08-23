<x-waterhole::layout :title="$channel->name">
    <x-waterhole::index.layout>

        @components(Waterhole\Extend\FeedHeader::getComponents(), compact('feed', 'channel'))

        <x-waterhole::feed.content :feed="$feed" :channel="$channel"/>

    </x-waterhole::index.layout>
</x-waterhole::layout>
