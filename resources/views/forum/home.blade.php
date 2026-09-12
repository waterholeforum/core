<x-waterhole::forum-layout show-sidebar :rss="route('waterhole.rss.posts')">
    <x-waterhole::index>
        <h1 class="visually-hidden">{{ config('waterhole.forum.name') }}</h1>

        <x-waterhole::home-feed />
    </x-waterhole::index>
</x-waterhole::forum-layout>
