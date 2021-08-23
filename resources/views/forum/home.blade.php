<x-waterhole::layout>
    <x-waterhole::index.layout>

        @components(Waterhole\Extend\FeedHeader::getComponents(), compact('feed'))

        <x-waterhole::feed.content :feed="$feed"/>

    </x-waterhole::index.layout>
</x-waterhole::layout>
