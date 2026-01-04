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
        'schema' => [
            '@type' => 'DiscussionForumPosting',
            'headline' => $post->title,
            'commentCount' => $post->comment_count,
        ],
    ]"
>

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

            @can('waterhole.post.comment', $post)
                <x-waterhole::composer :post="$post" :parent="$comment" />
            @endcan
        </div>
    </div>
</x-waterhole::layout>
