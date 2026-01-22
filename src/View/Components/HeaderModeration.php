<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;

class HeaderModeration extends Component
{
    public function shouldRender()
    {
        $channelIds = Channel::allPermitted(auth()->user(), 'moderate');

        return $channelIds === null || !empty($channelIds);
    }

    public function render()
    {
        return $this->view('waterhole::components.header-moderation');
    }
}
