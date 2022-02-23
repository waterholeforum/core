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

            <h1 class="h2">{{ $title }}</h1>
        </header>

        <turbo-frame id="@domid($comment)">
            <x-waterhole::comment-full
                :comment="$comment"
                with-replies
            />
        </turbo-frame>

        <x-waterhole::composer
            :post="$post"
            :parent="$comment"
            class="can-sticky"
        />
    </div>
</x-waterhole::layout>
