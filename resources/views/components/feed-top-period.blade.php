<ui-popup placement="bottom-start">
    <button class="btn btn--small" style="margin-left: var(--space-xs)">
        <span>{{ ucfirst($currentPeriod) ?: 'All Time' }}</span>
        <x-waterhole::icon icon="heroicon-s-chevron-down"/>
    </button>

    <ui-menu class="menu" hidden>
        @foreach ([null, ...$periods] as $period)
            <a
                href="{{ request()->fullUrlWithQuery(compact('period')) }}"
                class="menu-item"
                role="menuitemradio"
                @if ($currentPeriod === $period) aria-checked="true" @endif
            >
                <span>{{ ucfirst($period) ?: 'All Time' }}</span>
                @if ($currentPeriod === $period)
                    <x-waterhole::icon
                        icon="heroicon-s-check"
                        class="menu-item-check"
                    />
                @endif
            </a>
        @endforeach
    </ui-menu>
</ui-popup>
