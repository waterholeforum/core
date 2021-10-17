<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    public ?string $icon;

    public function __construct(string $icon = null)
    {
        $this->icon = $icon;
    }

    public function render()
    {
        return view('waterhole::components.icon');
    }
}
