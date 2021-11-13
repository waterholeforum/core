<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\PostFeed;

class FeedSort extends Component
{
    public PostFeed|\Waterhole\Feed $feed;
    public ?Channel $channel;

    public function __construct(PostFeed|\Waterhole\Feed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel;
    }

    public function render()
    {
        return view('waterhole::components.feed-sort');
    }
}
