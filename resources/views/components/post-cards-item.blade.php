<article
    {{ $attributes
        ->class('card card__body post-card stack gap-lg')
        ->merge(Waterhole\Extend\PostAttributes::build($post)) }}
    data-controller="post"
>
    <header class="post-card__header row justify-between align-start">
        <x-waterhole::post-summary :post="$post"/>
        <x-waterhole::post-actions :post="$post"/>
    </header>

    <div class="post-card__content content content--compact text-sm">
        <x-waterhole::truncate :html="$post->body_html">
            <p>
                <a href="{{ $post->url }}" class="weight-bold">
                    {{ __('waterhole::forum.post-read-more-link') }}
                </a>
            </p>
        </x-waterhole::truncate>
    </div>

    <div class="row gap-xs wrap">
        @components(Waterhole\Extend\PostFooter::build(), compact('post'))
    </div>
</article>
