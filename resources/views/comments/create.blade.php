<x-waterhole::layout :title="$post->title">
    <div class="section container stack-lg">
        <h1 class="h3">
            <a href="{{ $parent ? $parent->post_url : $post->url }}" class="with-icon">
                <x-waterhole::icon icon="heroicon-o-arrow-left"/>
                <span data-page-target="title">{{ $post->title }}</span>
            </a>
        </h1>

        <x-waterhole::composer
            :post="$post"
            :parent="$parent"
            class="is-open"
        />
    </div>
</x-waterhole::layout>
