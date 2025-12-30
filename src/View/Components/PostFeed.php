<?php

namespace Waterhole\View\Components;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\View\Component;
use Waterhole\Filters\Latest;
use Waterhole\Filters\Newest;
use Waterhole\Models\Channel;

class PostFeed extends Component
{
    public bool $showLastVisit;
    public CursorPaginator $posts;
    public ?array $publicChannels;
    public array $channels;

    public function __construct(
        public \Waterhole\Feed\PostFeed $feed,
        public ?Channel $channel = null,
    ) {
        $this->channel = $channel?->exists ? $channel : null;

        $filter = $feed->currentFilter;
        $this->showLastVisit = $filter instanceof Newest || $filter instanceof Latest;

        $this->publicChannels = Channel::allPermitted(null);
        $this->channels = $this->channel ? [$this->channel->id] : Channel::pluck('id')->all();
        $this->posts = $feed->items()->withQueryString();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-feed');
    }
}
