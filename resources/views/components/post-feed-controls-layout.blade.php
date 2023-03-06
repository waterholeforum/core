<h4 class="menu-heading">
    {{ __('waterhole::forum.post-feed-controls-layout-heading') }}
</h4>

<x-waterhole::menu-item
    icon="tabler-list"
    :label="__('waterhole::system.layout-list')"
    :href="request()->fullUrlWithQuery(['layout' => 'list'])"
    :active="$feed->currentLayout() === 'list'"
/>

<x-waterhole::menu-item
    icon="tabler-layout-list"
    :label="__('waterhole::system.layout-cards')"
    :href="request()->fullUrlWithQuery(['layout' => 'cards'])"
    :active="$feed->currentLayout() === 'cards'"
/>
