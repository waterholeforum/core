<x-waterhole::layout>
    <x-slot:head>
        <link rel="alternate" type="application/atom+xml" href="{{ route('waterhole.atom.posts') }}">
    </x-slot:head>

    <x-waterhole::index>
        <x-waterhole::post-feed :feed="$feed"/>
    </x-waterhole::index>
</x-waterhole::layout>
