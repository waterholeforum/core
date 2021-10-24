<article class="post-list-item {{ Waterhole\Extend\PostClasses::getClasses($post) }}">
    <div class="post-list-item__content toolbar">
        @components(Waterhole\Extend\PostListItem::getComponents(), compact('post'))
    </div>
    <div class="post-list-item__controls">
        <x-waterhole::post-actions :post="$post"/>
    </div>
</article>
