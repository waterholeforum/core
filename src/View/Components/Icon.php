<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    public function __construct(public ?string $icon = null)
    {
    }

    public function render()
    {
        return view('waterhole::components.icon');
    }
}
