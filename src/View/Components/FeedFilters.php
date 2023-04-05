<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Waterhole\Feed\Feed;
use Waterhole\Filters\Filter;

class FeedFilters extends Component
{
    public function __construct(public Feed $feed, public int $limit = 4)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.feed-filters');
    }

    public function url(Filter $filter): string
    {
        return request()->url() . '?' . Arr::query(['filter' => $filter->handle()]);
    }
}
