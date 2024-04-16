<ui-popup placement="bottom-end" data-controller="notifications-popup" data-turbo-permanent>
    <a
        href="{{ route('waterhole.notifications.index') }}"
        class="btn btn--icon btn--transparent"
        data-action="notifications-popup#open"
        role="button"
    >
        @icon('tabler-bell')

        <x-waterhole::notifications-badge :user="Auth::user()" />

        <ui-tooltip>{{ __('waterhole::notifications.title') }}</ui-tooltip>
    </a>

    <ui-menu hidden class="menu notifications-menu">
        <turbo-frame
            data-id="notifications"
            data-controller="turbo-frame"
            src="{{ route('waterhole.notifications.index') }}"
            loading="lazy"
            data-notifications-popup-target="frame"
            class="busy-spinner"
        ></turbo-frame>
    </ui-menu>

    {{-- To detect the screen size and determine whether to open the popup vs. follow the link --}}
    <div class="hide-sm" data-notifications-popup-target="sm"></div>

    <x-turbo::stream-from
        :source="Auth::user()"
        type="private"
        data-action="message->page#incrementDocumentTitle"
    />
</ui-popup>
