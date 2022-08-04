<ui-popup placement="top-start" class="js-only" data-controller="theme">
    <button class="btn btn--icon btn--transparent text-xs">
        <x-waterhole::icon icon="heroicon-o-sun" class="light-only"/>
        <x-waterhole::icon icon="heroicon-o-moon" class="dark-only"/>
        <ui-tooltip>Theme</ui-tooltip>
    </button>

    <ui-menu class="menu" hidden>
        <button class="menu-item" role="menuitemradio" data-action="theme#set" data-theme-name-param="light">
            <x-waterhole::icon icon="heroicon-o-sun"/>
            <span>Light</span>
            <x-waterhole::icon icon="heroicon-s-check" class="menu-item__check"/>
        </button>

        <button class="menu-item" role="menuitemradio" data-action="theme#set" data-theme-name-param="dark">
            <x-waterhole::icon icon="heroicon-o-moon"/>
            <span>Dark</span>
            <x-waterhole::icon icon="heroicon-s-check" class="menu-item__check"/>
        </button>

        <button class="menu-item" role="menuitemradio" data-action="theme#set" data-theme-name-param>
            <x-waterhole::icon icon="heroicon-o-desktop-computer"/>
            <span>Automatic</span>
            <x-waterhole::icon icon="heroicon-s-check" class="menu-item__check"/>
        </button>
    </ui-menu>
</ui-popup>
