<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Filters\Latest;
use Waterhole\Filters\NewActivity;
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
        $this->showLastVisit = $filter instanceof Latest || $filter instanceof NewActivity;
    }

    public function render()
    {
        return view('waterhole::components.post-feed');
    }
}
