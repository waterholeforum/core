<x-waterhole::selector
    :value="$currentPeriod"
    :options="[null, ...$periods]"
    :label="fn($period) => __('waterhole::forum.filter-top-' . ($period ?: 'all-time'))"
    :href="fn($period) => request()->fullUrlWithQuery(compact('period'))"
    button-class="btn btn--small"
    style="margin-left: var(--space-xs)"
    placement="bottom-start"
/>
