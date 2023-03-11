<x-waterhole::layout>
    <x-slot:head>
        <link rel="alternate" type="application/rss+xml" href="{{ route('waterhole.rss.posts') }}">
    </x-slot:head>

    <x-waterhole::index>
        <x-waterhole::post-feed :feed="$feed"/>
    </x-waterhole::index>
</x-waterhole::layout>
