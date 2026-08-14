<ui-popup class="header-sidebar js-only" data-global-sidebar-target="popup">
    <button
        type="button"
        class="btn btn--icon btn--transparent btn--start text-md mr-xxs"
        aria-label="{{ __('waterhole::forum.menu-button') }}"
    >
        @icon('tabler-menu-2')
        <ui-tooltip>{{ __('waterhole::forum.menu-button') }}</ui-tooltip>
    </button>

    <div
        hidden
        class="drawer global-sidebar-drawer"
        data-global-sidebar-target="drawer"
    ></div>
</ui-popup>
