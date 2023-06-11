<x-waterhole::layout>
    <x-slot name="head">
        <link
            rel="alternate"
            type="application/rss+xml"
            href="{{ route('waterhole.rss.posts') }}"
        />
    </x-slot>

    <x-waterhole::index>
        <x-waterhole::post-feed :feed="$feed" />
    </x-waterhole::index>
</x-waterhole::layout>
