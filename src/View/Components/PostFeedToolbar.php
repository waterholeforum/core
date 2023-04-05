<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Feed\PostFeed;
use Waterhole\Models\Channel;

class PostFeedToolbar extends Component
{
    public function __construct(public PostFeed $feed, public ?Channel $channel = null)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.post-feed-toolbar');
    }
}
