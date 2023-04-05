<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;

class ChannelLabel extends Component
{
    public function __construct(public ?Channel $channel, public bool $link = false)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.channel-label');
    }
}
