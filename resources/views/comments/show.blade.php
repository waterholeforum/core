<x-waterhole::layout :title="'Comment in '.$post->title">
    <div class="container section container stack-lg">
        <h1 class="h3">
            <a href="{{ $comment->post_url }}" class="with-icon">
                <x-waterhole::icon icon="heroicon-o-arrow-left"/>
                <span>{{ $post->title }}</span>
            </a>
        </h1>

        <div class="stack-md">
            <h2 class="h4 color-muted">Comment #{{ $comment->index() + 1 }}</h2>

            <x-waterhole::comment-full :comment="$comment" with-replies/>
        </div>

        <x-waterhole::composer
            :post="$post"
            :parent="$comment"
            class="can-sticky"
        />
    </div>
</x-waterhole::layout>
