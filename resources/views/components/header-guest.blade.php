@if (Route::has('waterhole.register'))
    <a
        href="{{ route('waterhole.register') }}"
        class="header-register btn btn--transparent btn--narrow color-accent text-xs hide-sm"
    >
        {{ __('waterhole::forum.register') }}
    </a>
@endif

@if (Route::has('waterhole.login'))
    <a
        href="{{ route('waterhole.login') }}"
        class="header-login btn bg-accent mx-xs text-xs btn--narrow"
    >
        @icon('tabler-user-filled')
        {{ __('waterhole::forum.log-in') }}
    </a>
@endif

<ui-popup placement="bottom-end" class="js-only">
    <button type="button" class="btn btn--icon btn--transparent">
        @icon('tabler-dots')
        <ui-tooltip>{{ __('waterhole::system.more-button') }}</ui-tooltip>
    </button>

    <ui-menu class="menu" hidden>
        <x-waterhole::theme-menu-item />
        <x-waterhole::language-menu-item />
    </ui-menu>
</ui-popup>
