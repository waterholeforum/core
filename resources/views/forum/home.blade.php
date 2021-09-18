<x-waterhole::layout>
    <x-waterhole::index>

        @components(Waterhole\Extend\FeedHeader::getComponents(), compact('feed'))

        <x-waterhole::feed-content :feed="$feed"/>

    </x-waterhole::index>
</x-waterhole::layout>
