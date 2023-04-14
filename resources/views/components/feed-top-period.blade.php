<x-waterhole::selector
    :value="$currentPeriod"
    :options="[null, ...$periods]"
    :label="fn($period) => __('waterhole::forum.filter-top-' . ($period ?: 'all-time'))"
    :href="fn($period) => request()->fullUrlWithQuery(compact('period'))"
    button-class="tab"
    placement="bottom-start"
/>
