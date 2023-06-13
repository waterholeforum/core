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
            data-controller="turbo-frame"
            data-action="turbo:frame-load->turbo-frame#remove"
            data-action-menu-target="frame"
        >
            <x-waterhole::spinner class="spinner--block" />
        </turbo-frame>
    </ui-menu>
</ui-popup>
