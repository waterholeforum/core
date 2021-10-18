<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\PostFeed;
use Waterhole\Sorts\Top;

class FeedTopPeriod extends Component
{
    public PostFeed $feed;
    public ?Channel $channel;
    public ?array $periods = null;
    public ?string $currentPeriod = null;

    public function __construct(PostFeed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel;

        $sort = $feed->currentSort();
        if ($sort instanceof Top) {
            $this->periods = $sort::PERIODS;
            $this->currentPeriod = $sort->currentPeriod();
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
