<ui-popup placement="bottom-end" data-controller="notifications-popup" data-persistent-badge>
    <a
        href="{{ route('waterhole.saved.index') }}"
        class="btn btn--icon btn--transparent"
        data-action="notifications-popup#open"
        data-turbo-prefetch="false"
        role="button"
    >
        @icon('tabler-bookmark')

        <ui-tooltip>{{ __('waterhole::forum.saved-title') }}</ui-tooltip>
    </a>

    @unless (request()->routeIs('waterhole.saved.*'))
        <ui-menu hidden class="menu saved-menu">
            <turbo-frame
                id="saved"
                data-turbo-permanent
                src="{{ route('waterhole.saved.index') }}"
                loading="lazy"
                data-notifications-popup-target="frame"
                class="busy-spinner"
            ></turbo-frame>
        </ui-menu>
    @endunless

    {{-- To detect the screen size and determine whether to open the popup vs. follow the link --}}
    <div class="hide-sm" data-notifications-popup-target="sm"></div>
</ui-popup>
