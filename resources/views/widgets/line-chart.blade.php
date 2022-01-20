<div class="card full-height line-chart-widget stack-xs" data-controller="line-chart">
    <div class="line-chart-widget__head stack-xs">
        <div class="row justify-between">
            <h3>{{ $title }}</h3>

            <ui-popup placement="bottom-end">
                <button class="btn btn--small btn--transparent btn--inline">
                    <span>{{ Str::headline($selectedPeriod) }}</span>
                    <x-waterhole::icon icon="heroicon-s-selector"/>
                </button>

                <ui-menu class="menu" hidden>
                    @foreach ($periods as $period => $info)
                    <a
                        href="{{ route('waterhole.admin.dashboard.widget', compact('id')) }}?period={{ $period }}"
                        class="menu-item"
                        role="menuitemradio"
                        @if ($selectedPeriod === $period) aria-checked="true" @endif
                    >
                        {{ Str::headline($period) }}
                        @if ($selectedPeriod === $period)
                            <x-waterhole::icon icon="heroicon-s-check" class="menu-item-check"/>
                        @endif
                    </a>
                    @endforeach
                </ui-menu>
            </ui-popup>
        </div>

        <div style="height: 2em">
            <div class="row gap-sm align-baseline" data-line-chart-target="summary">
                <span class="text-lg">{{ number_format($periodTotal) }}</span>
                @if ($prevPeriodTotal && $periodTotal !== $prevPeriodTotal)
                    <span class="badge badge--{{ $periodTotal < $prevPeriodTotal ? 'warning' : 'success' }}">
                        <x-waterhole::icon :icon="$periodTotal < $prevPeriodTotal ? 'heroicon-s-arrow-down' : 'heroicon-s-arrow-up'"/>
                        {{ number_format(abs(round(($periodTotal - $prevPeriodTotal) / $prevPeriodTotal * 100))) }}%
                    </span>
                @endif
            </div>

            <div class="row gap-sm align-baseline" data-line-chart-target="legend" hidden></div>
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
                    <th>Current Period</th>
                    @for ($i = $periodStart; $i < $periodEnd; $i = $i->add(1, $selectedUnit))
                        <td>{{ $results->where('date', '>=', $i)->where('date', '<', $i->add(1, $selectedUnit))->sum('count') }}</td>
                    @endfor
                </tr>
                <tr>
                    <th>Previous Period</th>
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
        class="row gap-md justify-between color-muted text-xxs"
        aria-hidden="true"
        hidden
    >
        <div>{{ Str::before($units[$selectedUnit]['label']($periodStart), ' - ') }}</div>
        <div>{{ Str::before($units[$selectedUnit]['label']($periodEnd), ' - ') }}</div>
    </div>
</div>
