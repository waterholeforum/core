<?php

namespace Waterhole\Widgets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LineChart extends Component
{
    public static bool $lazy = true;

    public int $id;
    public string $title;
    public array $periods;
    public array $units;
    public string $selectedPeriod;
    public string $selectedUnit;
    public CarbonImmutable $periodStart;
    public CarbonImmutable $periodEnd;
    public CarbonImmutable $prevPeriodStart;
    public CarbonImmutable $prevPeriodEnd;
    public Collection $results;
    public int $periodTotal;
    public int $prevPeriodTotal;

    public function __construct(
        int $id,
        string $title,
        $model,
        string $column = 'created_at',
        string $defaultPeriod = 'last_7_days'
    ) {
        $this->id = $id;
        $this->title = $title;

        $this->periods = [
            'today' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfDay(),
                'end' => fn(CarbonImmutable $date) => $date->endOfDay(),
                'units' => ['hour'],
            ],
            'last_7_days' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfDay()->subDays(6),
                'end' => fn(CarbonImmutable $date) => $date->endOfDay(),
                'units' => ['day', 'hour'],
            ],
            'last_4_weeks' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfWeek()->subWeeks(3),
                'end' => fn(CarbonImmutable $date) => $date->endOfWeek(),
                'units' => ['day', 'week'],
            ],
            'last_3_months' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfMonth()->subMonths(2),
                'end' => fn(CarbonImmutable $date) => $date->endOfMonth(),
                'units' => ['week', 'month'],
            ],
            'last_12_months' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfMonth()->subMonths(11),
                'end' => fn(CarbonImmutable $date) => $date->endOfMonth(),
                'units' => ['month', 'week'],
            ],
            'this_month' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfMonth(),
                'end' => fn(CarbonImmutable $date) => $date->endOfMonth(),
                'units' => ['day', 'week'],
            ],
            'this_quarter' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfQuarter(),
                'end' => fn(CarbonImmutable $date) => $date->endOfQuarter(),
                'units' => ['day', 'week'],
            ],
            'this_year' => [
                'start' => fn(CarbonImmutable $date) => $date->startOfYear(),
                'end' => fn(CarbonImmutable $date) => $date->endOfYear(),
                'units' => ['month', 'week'],
            ],
            'all_time' => [
                'start' => fn() => CarbonImmutable::parse($model::min($column)),
                'end' => fn(CarbonImmutable $date) => $date,
                'units' => ['month', 'year'],
            ],
        ];

        $this->units = [
            'hour' => [
                'format' => '%Y-%m-%d %H:00:00',
                'label' => fn(CarbonImmutable $date) => $date->format('g:i a'),
            ],
            'day' => [
                'format' => '%Y-%m-%d',
                'label' => fn(CarbonImmutable $date) => $date->format('d M'),
            ],
            'week' => [
                'format' => '%YW%v',
                'label' => fn(CarbonImmutable $date) => $date->format('d M').' - '.$date->addDays(6)->format('d M'),
            ],
            'month' => [
                'format' => '%Y-%m-01',
                'label' => fn(CarbonImmutable $date) => $date->format('M Y'),
            ],
            'year' => [
                'format' => '%Y',
                'label' => fn(CarbonImmutable $date) => $date->format('Y'),
            ],
        ];

        $this->selectedPeriod = isset($this->periods[$p = request('period')]) ? $p : $defaultPeriod;

        $period = $this->periods[$this->selectedPeriod];
        $now = CarbonImmutable::now();
        $this->periodStart = $period['start']($now);
        $this->periodEnd = $period['end']($now);
        $this->prevPeriodStart = $period['start']($this->periodStart->subSecond());
        $this->prevPeriodEnd = $period['end']($this->periodStart->subSecond());

        $this->selectedUnit = $period['units'][0];

        $unit = $this->units[$this->selectedUnit];

        if (! $model instanceof QueryBuilder && ! $model instanceof EloquentBuilder) {
            $model = $model::query();
        }

        $this->results = $model
            ->selectRaw("DATE_FORMAT($column, ?) as time_group", [$unit['format']])
            ->selectRaw('COUNT(*) as count')
            ->where($column, '>=', $this->prevPeriodStart)
            ->where($column, '<', $this->periodEnd)
            ->groupBy('time_group')
            ->get(['count', 'time_group'])
            ->map(fn($row) => [
                'count' => $row['count'],
                'date' => CarbonImmutable::parse($row['time_group']),
            ]);

        $this->periodTotal = $this->results
            ->where('date', '>=', $this->periodStart)
            ->where('date', '<', $this->periodEnd)
            ->sum('count');

        $this->prevPeriodTotal = $this->results
            ->where('date', '>=', $this->prevPeriodStart)
            ->where('date', '<', $this->prevPeriodEnd)
            ->sum('count');
    }

    public function render()
    {
        return view('waterhole::widgets.line-chart');
    }
}
