<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Feed\Feed;
use Waterhole\Filters\Filter;
use Waterhole\Filters\Top;

class FeedFilters extends Component
{
    public Collection $promotedComponents;
    public Collection $overflowComponents;
    public Collection $systemComponents;
    public ?NavLink $activeOverflowComponent;

    public function __construct(Feed $feed, int $limit = 3)
    {
        $toComponent = fn(Filter $filter) => (new NavLink(
            label: $filter->label(),
            icon: $filter->icon,
            href: $this->url($filter),
            active: $feed->currentFilter === $filter,
        ))->withAttributes(['class' => 'tab']);

        [$systemFilters, $configuredFilters] =
            $feed->filters->partition(fn(Filter $filter) => $filter->isSystem);
        $configuredComponents = $configuredFilters->map($toComponent);

        $this->promotedComponents = $configuredComponents->take($limit);
        $this->overflowComponents = $configuredComponents->slice($limit);
        $this->systemComponents = $systemFilters->map($toComponent);
        $this->activeOverflowComponent = $this->overflowComponents
            ->concat($this->systemComponents)
            ->firstWhere('isActive');
    }

    public function render()
    {
        return $this->view('waterhole::components.feed-filters');
    }

    private function url(Filter $filter): string
    {
        return request()->fullUrlWithQuery([
            'filter' => $filter->handle(),
            'period' => $filter instanceof Top ? request('period') : null,
            'cursor' => null,
            'page' => null,
        ]);
    }
}
