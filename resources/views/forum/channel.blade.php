<x-waterhole::layout :title="$channel->name" :data-channel="$channel->slug">
    <x-slot:head>
        <link rel="alternate" type="application/atom+xml" href="{{ route('waterhole.atom.channel', compact('channel')) }}">
    </x-slot:head>

    <x-waterhole::index :channel="$channel">
        <x-waterhole::post-feed
            :feed="$feed"
            :channel="$channel"
        />
    </x-waterhole::index>
</x-waterhole::layout>
