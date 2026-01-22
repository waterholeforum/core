<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Feed\Feed;
use Waterhole\Filters\Filter;

class FeedFilters extends Component
{
    public Collection $components;
    public Collection $firstComponents;
    public Collection $overflowComponents;
    public NavLink $activeComponent;

    public function __construct(public Feed $feed, public int $limit = 4)
    {
        $this->components = $feed->filters->map(
            fn($filter) => (new NavLink(
                label: $filter->label(),
                href: $this->url($filter),
                active: $feed->currentFilter === $filter,
            ))->withAttributes(['class' => 'tab']),
        );

        $this->firstComponents = $this->components->take($limit);
        $this->overflowComponents = $this->components->slice($limit);
        $this->activeComponent = $this->components->first->isActive;
    }

    public function render()
    {
        return $this->view('waterhole::components.feed-filters');
    }

    public function url(Filter $filter): string
    {
        return request()->fullUrlWithQuery(['filter' => $filter->handle(), 'page' => null]);
    }
}
