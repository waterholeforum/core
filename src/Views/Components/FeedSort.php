<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Feed\Feed;
use Waterhole\Models\Channel;

class FeedSort extends Component
{
    public Feed $feed;

    public ?Channel $channel;

    public function __construct(Feed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel;
    }

    public function render()
    {
        return view('waterhole::components.feed-sort');
    }
}
