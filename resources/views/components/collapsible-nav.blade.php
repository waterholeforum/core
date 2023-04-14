<ui-popup {{ $attributes->merge(['class' => 'collapsible-nav stack']) }}>
    <button class="btn text-md {{ $buttonClass }}">
        @isset ($button)
            {{ $button }}
        @elseif ($activeComponent)
            @icon($activeComponent->icon)
            <span class="overflow-ellipsis">{{ $activeComponent->label }}</span>
            @icon('tabler-selector')
        @elseif (isset($empty))
            {{ $empty }}
        @else
            @icon('tabler-menu-2')
            <span>{{ __('waterhole::forum.menu-button') }}</span>
        @endisset
    </button>

    <div hidden class="drawer">
        <nav class="nav {{ $navClass }}">
            @components($components)
        </nav>
    </div>
</ui-popup>
