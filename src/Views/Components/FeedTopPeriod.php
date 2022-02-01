<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Feed\Feed;
use Waterhole\Filters\Top;
use Waterhole\Models\Channel;

class FeedTopPeriod extends Component
{
    public Feed $feed;
    public ?Channel $channel;
    public ?array $periods = null;
    public ?string $currentPeriod = null;

    public function __construct(Feed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel;

        $filter = $feed->currentFilter();

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
        return view('waterhole::components.feed-top-period');
    }
}
