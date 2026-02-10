<ui-popup placement="right-start" class="js-only" data-controller="theme">
    <button type="button" class="menu-item">
        @icon('tabler-brightness')
        <span>{{ __('waterhole::system.theme-button') }}</span>
        @icon('tabler-chevron-right', ['class' => 'push-end'])
    </button>

    <ui-menu class="menu" hidden>
        <button
            type="button"
            class="menu-item"
            role="menuitemradio"
            data-action="theme#set"
            data-theme-name-param="light"
        >
            @icon('tabler-sun')
            <span>{{ __('waterhole::system.theme-light') }}</span>
            @icon('tabler-check', ['class' => 'menu-item__check'])
        </button>

        <button
            type="button"
            class="menu-item"
            role="menuitemradio"
            data-action="theme#set"
            data-theme-name-param="dark"
        >
            @icon('tabler-moon')
            <span>{{ __('waterhole::system.theme-dark') }}</span>
            @icon('tabler-check', ['class' => 'menu-item__check'])
        </button>

        <button
            type="button"
            class="menu-item"
            role="menuitemradio"
            data-action="theme#set"
            data-theme-name-param
        >
            @icon('tabler-device-desktop')
            <span>{{ __('waterhole::system.theme-automatic') }}</span>
            @icon('tabler-check', ['class' => 'menu-item__check'])
        </button>
    </ui-menu>
</ui-popup>
