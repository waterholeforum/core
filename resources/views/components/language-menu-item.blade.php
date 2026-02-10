<ui-popup placement="right-start" class="js-only">
    <button type="button" class="menu-item">
        @icon('tabler-language')
        <span>{{ __('waterhole::system.language-button') }}</span>
        @icon('tabler-chevron-right', ['class' => 'push-end'])
    </button>

    <ui-menu class="menu" hidden>
        @foreach ($locales as $locale => $name)
            <x-waterhole::menu-item
                :label="$name ?? $locale"
                :href="'?locale=' . $locale"
                :active="$locale === $currentLocale"
                data-turbo="false"
            />
        @endforeach
    </ui-menu>
</ui-popup>
