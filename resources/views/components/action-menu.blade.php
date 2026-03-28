<ui-popup
    {{
        $attributes
            ->merge([
                'data-controller' => $attributes->prepends('action-menu'),
                'data-shortcut-selection-owner' => ($owner = $attributes->get('data-shortcut-selection-owner', dom_id($for))),
            ])
            ->class('row')
    }}
>
    <a
        href="{{ $url }}"
        role="button"
        data-action="mouseenter->action-menu#preload"
        {{
            (new Illuminate\View\ComponentAttributeBag($buttonAttributes))->merge([
                'data-shortcut-trigger' => 'selection.actions',
                'data-shortcut-selection-owner' => $owner,
            ])
        }}
    >
        @isset($button)
            {{ $button }}
        @else
            @icon('tabler-dots')

            <ui-tooltip>
                {{ __('waterhole::system.actions-button') }}
                <x-waterhole::shortcut-label shortcut="selection.actions" />
            </ui-tooltip>
        @endisset
    </a>

    <ui-menu class="menu" hidden data-shortcut-hidden data-shortcut-context>
        <turbo-frame
            id="actions"
            loading="lazy"
            src="{{ $url }}"
            data-action-menu-target="frame"
            class="busy-spinner"
        ></turbo-frame>
    </ui-menu>
</ui-popup>
