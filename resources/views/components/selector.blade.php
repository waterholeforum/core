<ui-popup {{ $attributes }}>
    <button type="button" class="{{ $buttonClass }}">
        <span>{{ $label($value) }}</span>
        <x-waterhole::icon icon="heroicon-s-selector"/>
    </button>

    <ui-menu class="menu" hidden>
        @foreach ($options as $option)
            <a
                href="{{ $href($option) }}"
                class="menu-item"
                role="menuitemradio"
                @if ($value === $option) aria-checked="true" @endif
            >
                {{ $label($option) }}
                @if ($value === $option)
                    <x-waterhole::icon icon="heroicon-s-check" class="menu-item__check"/>
                @endif
            </a>
        @endforeach
    </ui-menu>
</ui-popup>
