<ui-popup {{ $attributes->merge(['class' => 'responsive-nav stack']) }}>
    <button class="btn text-md">
        @isset ($button)
            {{ $button }}
        @elseif ($activeComponent)
            <x-waterhole::icon :icon="$activeComponent->icon"/>
            <span class="overflow-ellipsis">{{ $activeComponent->label }}</span>
            <x-waterhole::icon icon="tabler-selector"/>
        @elseif (isset($empty))
            {{ $empty }}
        @else
            <x-waterhole::icon icon="tabler-menu-2"/>
            <span>{{ __('waterhole::forum.menu-button') }}</span>
        @endisset
    </button>

    <div hidden class="drawer">
        <nav class="nav">
            @components($components)
        </nav>
    </div>
</ui-popup>
