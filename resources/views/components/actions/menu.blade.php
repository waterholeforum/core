@props(['for', 'only' => null, 'except' => null, 'buttonAttributes' => []])

<x-waterhole::actions.buttons
    :for="$for"
    :only="$only"
    :except="$except"
    role="menuitem"
    class="menu-item"
>
    <x-slot name="before">
        <ui-popup {{ $attributes->merge(['placement' => 'bottom-start']) }}>
            @if (isset($button))
                {{ $button }}
            @else
                <button {{ (new Illuminate\View\ComponentAttributeBag($buttonAttributes))->class('btn btn--icon btn--transparent btn--small') }}>
                    <x-heroicon-s-dots-horizontal class="icon"/>
                </button>
            @endif
            <ui-menu class="menu" hidden>
    </x-slot>

    <x-slot name="after">
            </ui-menu>
        </ui-popup>
    </x-slot>
</x-waterhole::actions.buttons>
