<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;

class ChannelLabel extends Component
{
    public ?Channel $channel;
    public bool $link;

    public function __construct(?Channel $channel, bool $link = false)
    {
        $this->channel = $channel;
        $this->link = $link;
    }

    public function render()
    {
        return view('waterhole::components.channel-label');
    }
}
