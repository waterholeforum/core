<x-waterhole::layout title="Post Comment">
    <div class="container">
        <h1>Reply to {{ $post->title }}</h1>

        <x-waterhole::composer :post="$post" :errors="$errors" open/>
    </div>
</x-waterhole::layout>
