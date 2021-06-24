<x-waterhole::selector
    placement="top-start"
    button-class="btn btn--transparent btn--sm"
    :value="$currentLocale"
    :options="array_keys($locales)"
    :label="fn($locale) => $locales[$locale] ?? $locale"
    :href="fn($locale) => '?locale='.$locale"
/>
