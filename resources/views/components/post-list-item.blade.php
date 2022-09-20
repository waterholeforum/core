<article
    {{ $attributes->class(['post-list-item', Waterhole\Extend\PostClasses::build($post)]) }}
    data-controller="post"
>
    <div class="post-list-item__content row gap-sm">
        @components(Waterhole\Extend\PostListItem::build(), compact('post'))
    </div>
    <div class="post-list-item__controls">
        <x-waterhole::post-actions :post="$post"/>
    </div>
</article>
