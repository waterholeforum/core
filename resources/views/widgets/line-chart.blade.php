<div
    class="card card__body line-chart-widget stack gap-xs"
    data-controller="line-chart"
>
    <div class="line-chart-widget__head stack gap-xs">
        <div class="row justify-between">
            <h3 class="h4">{{ $title }}</h3>

            <x-waterhole::selector
                placement="bottom-end"
                button-class="btn btn--small btn--transparent btn--edge"
                :value="$selectedPeriod"
                :options="array_keys($periods)"
                :label="fn($period) => __('waterhole::admin.period-'.str_replace('_', '-', $period))"
                :href="fn($period) => route('waterhole.admin.dashboard.widget', ['id' => $id]).'?period='.$period"
            />
        </div>

        <div class="row gap-sm align-baseline" data-line-chart-target="summary">
            <span class="text-lg">{{ Waterhole\format_number($periodTotal) }}</span>
            @if ($prevPeriodTotal && $periodTotal !== $prevPeriodTotal)
                <span class="badge bg-{{ $color = $periodTotal < $prevPeriodTotal ? 'attention' : 'success' }}-light color-{{ $color }}">
                    <x-waterhole::icon :icon="$periodTotal < $prevPeriodTotal ? 'tabler-arrow-down' : 'tabler-arrow-up'"/>
                    {{ Waterhole\format_number(abs(round(($periodTotal - $prevPeriodTotal) / $prevPeriodTotal)), ['style' => 'percent']) }}
                </span>
            @endif
        </div>

        <div class="row gap-sm align-baseline" data-line-chart-target="legend" hidden>
            <span class="text-lg" data-line-chart-target="legendAmount"></span>
            <span class="text-xs color-muted" data-line-chart-target="legendPeriod"></span>
        </div>
    </div>

    <div class="table-container" data-line-chart-target="table">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    @for ($i = $periodStart; $i < $periodEnd; $i = $i->add(1, $selectedUnit))
                        <th data-timestamp="{{ $i->timestamp }}">{{ $units[$selectedUnit]['label']($i) }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>{{ __('waterhole::admin.period-current-heading') }}</th>
                    @for ($i = $periodStart; $i < $periodEnd; $i = $i->add(1, $selectedUnit))
                        <td>{{ $results->where('date', '>=', $i)->where('date', '<', $i->add(1, $selectedUnit))->sum('count') }}</td>
                    @endfor
                </tr>
                <tr>
                    <th>{{ __('waterhole::admin.period-previous-heading') }}</th>
                    @for ($i = $prevPeriodStart; $i < $prevPeriodEnd; $i = $i->add(1, $selectedUnit))
                        <td>{{ $results->where('date', '>=', $i)->where('date', '<', $i->add(1, $selectedUnit))->sum('count') }}</td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>

    <div
        data-line-chart-target="chart"
        class="line-chart-widget__chart grow"
        hidden
    ></div>

    <div
        data-line-chart-target="axis"
        class="line-chart-widget__axis row gap-md justify-between color-muted text-xxs"
        aria-hidden="true"
        hidden
    >
        <div>{{ Str::before($units[$selectedUnit]['label']($periodStart), ' - ') }}</div>
        <div>{{ Str::before($units[$selectedUnit]['label']($periodEnd), ' - ') }}</div>
    </div>
</div>
