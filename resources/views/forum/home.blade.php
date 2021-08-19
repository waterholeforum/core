<x-waterhole::layout>
    <x-waterhole::index>

        <div class="card home-toolbar">
            <div class="toolbar"> <!-- Extend\HomeToolbar -->
                <h1 class="visually-hidden">Home</h1>

                <x-waterhole::feed.sort :feed="$feed"/>

                <div class="spacer"></div>

                @can('create', Waterhole\Models\Post::class)
                    <a href="{{ route('waterhole.posts.create') }}">New Post</a>
                @endcan
            </div>
        </div>

        <x-waterhole::feed.list :feed="$feed"/>

    </x-waterhole::index>
</x-waterhole::layout>
