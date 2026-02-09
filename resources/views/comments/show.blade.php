@php
    $title = __('waterhole::forum.comment-number-title', ['number' => $comment->index + 1]);
@endphp

<x-waterhole::layout
    :title="$title.' - '.$post->title"
    :seo="[
        'description' => $comment->body_text,
        'url' => $comment->post_url,
        'type' => 'article',
        'noindex' => ! $post->channel->structure->is_listed,
        'schema' => false,
    ]"
>
    <div
        class="container section"
        itemscope
        itemtype="https://schema.org/DiscussionForumPosting"
        itemid="{{ $post->url }}"
    >
        <meta itemprop="headline" content="{{ $post->title }}" />
        <meta itemprop="datePublished" content="{{ $post->created_at?->toAtomString() }}" />
        @if ($post->edited_at)
            <meta itemprop="dateModified" content="{{ $post->edited_at?->toAtomString() }}" />
        @endif
        <meta itemprop="url" content="{{ $post->url }}" />
        <meta itemprop="commentCount" content="{{ $post->comment_count }}" />
        <span itemprop="author" itemscope itemtype="https://schema.org/Person" hidden>
            <meta itemprop="name" content="{{ Waterhole\username($post->user) }}" />
            @if ($post->user)
                <meta itemprop="url" content="{{ $post->user->url }}" />
            @endif
        </span>

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

            <x-waterhole::comment-frame
                :comment="$comment"
                with-replies
                class="card"
                with-structured-data
            />

            @can('waterhole.post.comment', $post)
                <x-waterhole::composer :post="$post" :parent="$comment" />
            @endcan
        </div>
    </div>
</x-waterhole::layout>
