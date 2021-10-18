<article class="card post-card {{ Waterhole\Extend\PostClasses::getClasses($post) }}" id="@domid($post, 'card')">
    <header class="post-card__header">
        <x-waterhole::post-summary :post="$post"/>
        <x-waterhole::action-menu :for="$post" placement="bottom-end"/>
    </header>

    <div class="post-card__content content">
        {!! Str::limit(strip_tags($post->body), 300, '... <a href="'.$post->url.'">More</a>') !!}
    </div>

    <x-waterhole::post-footer :post="$post" interactive/>
</article>
