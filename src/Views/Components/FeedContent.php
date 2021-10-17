<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\PostFeed;

class FeedContent extends Component
{
    public PostFeed $feed;
    public ?Channel $channel;

    public function __construct(PostFeed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel;
    }

    public function render()
    {
        return view('waterhole::components.feed-content');
    }
}
