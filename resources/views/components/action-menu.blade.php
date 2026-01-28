<ui-popup data-controller="action-menu" {{ $attributes->class('row') }}>
    <a
        href="{{ $url }}"
        role="button"
        data-action="mouseenter->action-menu#preload"
        {{ new Illuminate\View\ComponentAttributeBag($buttonAttributes) }}
    >
        @isset($button)
            {{ $button }}
        @else
            @icon('tabler-dots')
            <ui-tooltip>{{ __('waterhole::system.actions-button') }}</ui-tooltip>
        @endisset
    </a>

    <ui-menu class="menu" hidden>
        <turbo-frame
            id="actions"
            loading="lazy"
            src="{{ $url }}"
            data-action-menu-target="frame"
            class="busy-spinner"
        ></turbo-frame>
    </ui-menu>
</ui-popup>
