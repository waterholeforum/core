<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\View\Components\Concerns\Streamable;

class PostFeedChannel extends Component
{
    use Streamable;

    public function __construct(public ?Channel $channel = null)
    {
    }

    public function shouldRender(): bool
    {
        return (bool) $this->channel;
    }

    public function render()
    {
        return $this->view('waterhole::components.post-feed-channel');
    }
}
