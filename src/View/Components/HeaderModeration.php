<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;

class HeaderModeration extends Component
{
    public function shouldRender()
    {
        return Channel::allPermitted(auth()->user(), 'moderate') !== [];
    }

    public function render()
    {
        return $this->view('waterhole::components.header-moderation');
    }
}
