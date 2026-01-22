<ui-popup
    placement="bottom-end"
    data-controller="notifications-popup"
    data-turbo-permanent
    data-persistent-badge
>
    <a
        href="{{ route('waterhole.moderation') }}"
        class="btn btn--icon btn--transparent"
        data-action="notifications-popup#open"
        role="button"
    >
        @icon('tabler-flag')

        <x-waterhole::moderation-badge :user="Auth::user()" />

        <ui-tooltip>{{ __('waterhole::forum.moderation-title') }}</ui-tooltip>
    </a>

    <ui-menu hidden class="menu moderation-menu">
        <turbo-frame
            data-id="moderation"
            data-controller="turbo-frame"
            src="{{ route('waterhole.moderation') }}"
            loading="lazy"
            data-notifications-popup-target="frame"
            class="busy-spinner"
        ></turbo-frame>
    </ui-menu>

    {{-- To detect the screen size and determine whether to open the popup vs. follow the link --}}
    <div class="hide-sm" data-notifications-popup-target="sm"></div>
</ui-popup>
