@php
    $title = __('waterhole::forum.create-comment-title');
@endphp

<x-waterhole::layout :title="$title.' - '.$post->title">
    <div class="section container stack gap-lg">
        <header class="stack gap-xs">
            <ol class="breadcrumb">
                <li><a href="{{ $parent ? $parent->post_url : $post->url }}">{{ $post->title }}</a></li>
                <li aria-hidden="true"></li>
            </ol>

            <h1 class="h3">{{ $title }}</h1>
        </header>

        <x-waterhole::composer
            :post="$post"
            :parent="$parent"
            class="is-open is-static"
        />
    </div>
</x-waterhole::layout>
