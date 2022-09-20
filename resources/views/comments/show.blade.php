@php
    $title = __('waterhole::forum.comment-number-title', ['number' => $comment->index + 1]);
@endphp

<x-waterhole::layout :title="$title.' - '.$post->title">
    <div class="container section stack gap-lg">
        <header class="stack gap-xs">
            <ol class="breadcrumb">
                <li><a href="{{ $comment->post_url }}">{{ $post->title }}</a></li>
                <li aria-hidden="true"></li>
            </ol>

            <h1 class="h3">{{ $title }}</h1>
        </header>

        <x-waterhole::comment-frame :comment="$comment" with-replies/>

        @can('post.comment', $post)
            <x-waterhole::composer
                :post="$post"
                :parent="$comment"
            />
        @endcan
    </div>
</x-waterhole::layout>
