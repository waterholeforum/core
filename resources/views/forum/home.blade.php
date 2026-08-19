<x-waterhole::forum-layout show-sidebar>
    <x-slot name="head">
        <link
            rel="alternate"
            type="application/rss+xml"
            href="{{ route('waterhole.rss.posts') }}"
        />
    </x-slot>

    <x-waterhole::index>
        <h1 class="visually-hidden">{{ config('waterhole.forum.name') }}</h1>

        <div class="stack gap-lg">
            @components(resolve(\Waterhole\Extend\Ui\HomePage::class))
        </div>
    </x-waterhole::index>
</x-waterhole::forum-layout>
