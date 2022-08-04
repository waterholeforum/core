<ui-popup placement="top-start">
    <button class="btn btn--transparent btn--small">
        <span>{{ $locales[$currentLocale] ?? 'Language' }}</span>
        <x-waterhole::icon icon="heroicon-s-selector" class="icon--narrow" />
    </button>

    <ui-menu class="menu" hidden>
        @foreach ($locales as $locale => $name)
            <a
                href="?locale={{ $locale }}"
                role="menuitemradio"
                class="menu-item"
                @if ($locale === $currentLocale) aria-checked="true" @endif
            >
                {{ $name }}
                @if ($locale === $currentLocale) <x-waterhole::icon icon="heroicon-s-check" class="menu-item__check"/> @endif
            </a>
        @endforeach
</ui-popup>
