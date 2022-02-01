<article
    {{ $attributes->class(['card post-card', Waterhole\Extend\PostClasses::build($post)]) }}
    data-controller="post"
>
    <header class="post-card__header">
        <x-waterhole::post-summary :post="$post"/>
        <x-waterhole::post-actions :post="$post"/>
    </header>

    <div class="post-card__content content">
        {!! Str::limit(strip_tags($post->body), 300, '... <a href="'.$post->url.'">More</a>') !!}
    </div>

    <x-waterhole::post-footer :post="$post" interactive/>
</article>
