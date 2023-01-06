<article
    {{ $attributes
        ->class('post-list-item row gap-xs')
        ->merge(Waterhole\Extend\PostAttributes::build($post)) }}
    data-controller="post"
>
    <div class="post-list-item__content grow row gap-xs">
        @components(Waterhole\Extend\PostListItem::build(), compact('post'))
    </div>

    <div class="post-list-item__controls hide-xs">
        <x-waterhole::post-actions :post="$post"/>
    </div>
</article>
