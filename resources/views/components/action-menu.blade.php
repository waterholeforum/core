<ui-popup {{ $attributes->class('row') }}>
    <a
        href="{{ $url }}"
        role="button"
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
        <turbo-frame id="actions" loading="lazy" src="{{ $url }}" target="_top">
            <x-waterhole::spinner class="spinner--block" />
        </turbo-frame>
    </ui-menu>
</ui-popup>
