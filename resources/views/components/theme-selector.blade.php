<ui-popup placement="bottom-end" class="js-only" data-controller="theme">
    <button class="btn btn--icon btn--transparent">
        <x-waterhole::icon icon="tabler-sun" class="light-only"/>
        <x-waterhole::icon icon="tabler-moon" class="dark-only"/>
        <ui-tooltip>{{ __('waterhole::system.theme-button') }}</ui-tooltip>
    </button>

    <ui-menu class="menu" hidden>
        <button
            class="menu-item"
            role="menuitemradio"
            data-action="theme#set"
            data-theme-name-param="light"
        >
            <x-waterhole::icon icon="tabler-sun"/>
            <span>{{ __('waterhole::system.theme-light') }}</span>
            <x-waterhole::icon
                icon="tabler-check"
                class="menu-item__check"
            />
        </button>

        <button
            class="menu-item"
            role="menuitemradio"
            data-action="theme#set"
            data-theme-name-param="dark"
        >
            <x-waterhole::icon icon="tabler-moon"/>
            <span>{{ __('waterhole::system.theme-dark') }}</span>
            <x-waterhole::icon
                icon="tabler-check"
                class="menu-item__check"
            />
        </button>

        <button
            class="menu-item"
            role="menuitemradio"
            data-action="theme#set"
            data-theme-name-param
        >
            <x-waterhole::icon icon="tabler-device-desktop"/>
            <span>{{ __('waterhole::system.theme-automatic') }}</span>
            <x-waterhole::icon
                icon="tabler-check"
                class="menu-item__check"
            />
        </button>
    </ui-menu>
</ui-popup>
