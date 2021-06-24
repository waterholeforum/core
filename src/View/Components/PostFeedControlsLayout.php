<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Feed\PostFeed;
use Waterhole\Models\Channel;

class PostFeedControlsLayout extends Component
{
    public function __construct(public PostFeed $feed, public ?Channel $channel = null)
    {
    }

    public function render()
    {
        return view('waterhole::components.post-feed-controls-layout');
    }
}
