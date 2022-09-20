<ui-popup placement="bottom-start" class="index-nav-structure drawer">
    <button class="btn text-md sidebar__collapsed">
        @isset ($button)
            {{ $button }}
        @elseif ($activeComponent)
            <x-waterhole::icon :icon="$activeComponent->icon"/>
            <span>{{ $activeComponent->label }}</span>
            <x-waterhole::icon icon="tabler-selector"/>
        @elseif (isset($empty))
            {{ $empty }}
        @else
            <x-waterhole::icon icon="tabler-menu-2"/>
            <span>{{ __('waterhole::forum.menu-button') }}</span>
        @endisset
    </button>

    <div hidden>
        <nav class="nav">
            @components($components)
        </nav>
    </div>
</ui-popup>
