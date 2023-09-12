@php
    $title = __('waterhole::forum.comment-number-title', ['number' => $comment->index + 1]);
@endphp

<x-waterhole::layout :title="$title.' - '.$post->title">
    <x-slot name="head">
        @unless ($post->channel->structure->is_listed)
            <meta name="robots" content="noindex" />
        @endunless
    </x-slot>

    <div class="container section">
        <div class="measure stack gap-lg">
            <header class="stack gap-xs">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ $comment->post_url }}" class="inline-block">
                            {{ Waterhole\emojify($post->title) }}
                        </a>
                    </li>
                    <li aria-hidden="true"></li>
                </ol>

                <h1 class="h3">{{ $title }}</h1>
            </header>

            <x-waterhole::comment-frame :comment="$comment" with-replies class="card" />

            @can('post.comment', $post)
                <x-waterhole::composer :post="$post" :parent="$comment" />
            @endcan
        </div>
    </div>
</x-waterhole::layout>
