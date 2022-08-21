<h4 class="menu-heading">Display as</h4>
<a
    href="{{ request()->fullUrlWithQuery(['layout' => 'list']) }}"
    class="menu-item"
    role="menuitemradio"
    @if ($feed->currentLayout() === 'list') aria-checked="true" @endif
>
    <x-waterhole::icon icon="heroicon-o-view-list"/>
    <span>List</span>
    @if ($feed->currentLayout() === 'list')
        <x-waterhole::icon icon="heroicon-o-check" class="menu-item__check"/>
    @endif
</a>
<a
    href="{{ request()->fullUrlWithQuery(['layout' => 'cards']) }}"
    class="menu-item"
    role="menuitemradio"
    @if ($feed->currentLayout() === 'cards') aria-checked="true" @endif
>
    <x-waterhole::icon icon="heroicon-o-collection"/>
    <span>Cards</span>
    @if ($feed->currentLayout() === 'cards')
        <x-waterhole::icon icon="heroicon-o-check" class="menu-item__check"/>
    @endif
</a>
