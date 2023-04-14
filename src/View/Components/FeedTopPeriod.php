<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Feed\Feed;
use Waterhole\Filters\Top;

class FeedTopPeriod extends Component
{
    public ?array $periods = null;
    public ?string $currentPeriod = null;

    public function __construct(public Feed $feed)
    {
        $filter = $feed->currentFilter;

        if ($filter instanceof Top) {
            $this->periods = $filter::PERIODS;
            $this->currentPeriod = $filter->currentPeriod();
        }
    }

    public function shouldRender(): bool
    {
        return (bool) $this->periods;
    }

    public function render()
    {
        return $this->view('waterhole::components.feed-top-period');
    }
}
