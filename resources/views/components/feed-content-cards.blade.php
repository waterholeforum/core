@props(['posts'])

<div class="post-cards">
    @foreach ($posts as $post)
        <article class="card post-card {{ Waterhole\Extend\PostClasses::getClasses($post) }}">
            <header class="post-card__header">
                <x-waterhole::post-summary :post="$post"/>
                <x-waterhole::action-menu :for="$post"/>
            </header>

            <div class="post-card__content content">
                {!! nl2br(Str::limit($post->body, 300, '... <a href="'.$post->url.'">More</a>')) !!}
            </div>

            <x-waterhole::post-footer :post="$post"/>
        </article>
    @endforeach
</div>
