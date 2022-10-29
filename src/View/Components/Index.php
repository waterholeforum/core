<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;

class Index extends Component
{
    public function __construct(public ?Channel $channel = null)
    {
        $this->channel = $channel?->exists ? $channel : null;
    }

    public function render()
    {
        return view('waterhole::components.index');
    }
}
