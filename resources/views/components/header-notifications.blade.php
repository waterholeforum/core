<ui-popup placement="bottom-end" data-controller="notifications-popup">
    <a
        href="{{ route('waterhole.notifications.index') }}"
        class="btn btn--icon btn--transparent"
        data-action="notifications-popup#open"
        data-turbo-prefetch="false"
        role="button"
    >
        @icon('tabler-bell')

        <x-waterhole::notifications-badge :user="Auth::user()" />

        <ui-tooltip>{{ __('waterhole::notifications.title') }}</ui-tooltip>
    </a>

    @unless (request()->routeIs('waterhole.notifications.*'))
        <ui-menu hidden class="menu notifications-menu">
            <turbo-frame
                id="notifications"
                data-turbo-permanent
                src="{{ route('waterhole.notifications.index') }}"
                loading="lazy"
                data-notifications-popup-target="frame"
                class="busy-spinner"
            ></turbo-frame>
        </ui-menu>
    @endunless

    {{-- To detect the screen size and determine whether to open the popup vs. follow the link --}}
    <div class="hide-sm" data-notifications-popup-target="sm"></div>

    <x-turbo::stream-from
        :source="Auth::user()"
        type="private"
        data-action="message->page#incrementDocumentTitle"
    />
</ui-popup>
