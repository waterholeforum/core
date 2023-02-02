<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Filters\Latest;
use Waterhole\Filters\Newest;
use Waterhole\Models\Channel;

class PostFeed extends Component
{
    public bool $showLastVisit;

    public function __construct(
        public \Waterhole\Feed\PostFeed $feed,
        public ?Channel $channel = null,
    ) {
        $this->channel = $channel?->exists ? $channel : null;

        $filter = $feed->currentFilter();
        $this->showLastVisit = $filter instanceof Newest || $filter instanceof Latest;
    }

    public function render()
    {
        return view('waterhole::components.post-feed');
    }
}
