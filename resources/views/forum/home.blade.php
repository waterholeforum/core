<x-waterhole::layout>
    <x-waterhole::index>

        @components(Waterhole\Extend\FeedHeader::getComponents(), ['feed' => $feed, 'channel' => null])

        <x-waterhole::feed-content :feed="$feed"/>

    </x-waterhole::index>
</x-waterhole::layout>
