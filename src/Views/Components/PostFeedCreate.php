<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Feed\PostFeed;
use Waterhole\Models\Channel;

class PostFeedCreate extends Component
{
    public function __construct(public PostFeed $feed, public ?Channel $channel = null)
    {
    }

    public function render()
    {
        return view('waterhole::components.post-feed-create');
    }
}
