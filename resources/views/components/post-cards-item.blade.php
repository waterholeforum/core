<article
    {{ $attributes->class(['card card__body post-card', Waterhole\Extend\PostClasses::build($post)]) }}
    data-controller="post"
>
    <header class="post-card__header">
        <x-waterhole::post-summary :post="$post"/>
        <x-waterhole::post-actions :post="$post"/>
    </header>

    <div class="post-card__content content content--compact text-sm">
        {!! $excerpt !!}

        @if ($truncated)
            <p>
                <a href="{{ $post->url }}" class="weight-bold">
                    {{ __('waterhole::forum.post-read-more-link') }}
                </a>
            </p>
        @endif
    </div>

    <div class="row gap-xs wrap">
        @components(Waterhole\Extend\PostFooter::build(), compact('post'))
    </div>
</article>
