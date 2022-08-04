<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Feed\PostFeed;
use Waterhole\Filters\Latest;
use Waterhole\Filters\NewActivity;
use Waterhole\Models\Channel;

class Feed extends Component
{
    public PostFeed $feed;

    public ?Channel $channel;

    public bool $showLastVisit;

    public function __construct(PostFeed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel?->exists ? $channel : null;

        $filter = $feed->currentFilter();
        $this->showLastVisit = $filter instanceof Latest || $filter instanceof NewActivity;
    }

    public function render()
    {
        return view('waterhole::components.feed');
    }
}
