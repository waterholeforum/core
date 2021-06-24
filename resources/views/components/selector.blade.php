<ui-popup {{ $attributes }}>
    <button type="button" class="{{ $buttonClass }}">
        @isset($button)
            {{ $button }}
        @else
            <span>{{ $value ? $label($value) : ($placeholder ?? $label(null)) }}</span>
            <x-waterhole::icon icon="tabler-selector" class="icon--narrow"/>
        @endisset
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
                    <x-waterhole::icon icon="tabler-check" class="menu-item__check"/>
                @endif
            </a>
        @endforeach
    </ui-menu>
</ui-popup>
