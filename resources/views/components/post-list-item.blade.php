<li class="post-list-item {{ Waterhole\Extend\PostClasses::getClasses($post) }}" id="@domid($post, 'list-item')">
    <div class="post-list-item__content toolbar">
        @components(Waterhole\Extend\PostListItem::getComponents(), compact('post'))
    </div>
    <x-waterhole::action-menu :for="$post" class="post-list-item__controls" placement="bottom-end"/>
</li>
