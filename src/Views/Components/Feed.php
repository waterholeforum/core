<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\PostFeed;
use Waterhole\Sorts\Latest;
use Waterhole\Sorts\NewActivity;

class Feed extends Component
{
    public PostFeed $feed;
    public ?Channel $channel;
    public bool $showLastVisit;

    public function __construct(PostFeed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel?->exists ? $channel : null;

        $sort = $feed->currentSort();
        $this->showLastVisit = $sort instanceof Latest || $sort instanceof NewActivity;
    }

    public function render()
    {
        return view('waterhole::components.feed');
    }
}
