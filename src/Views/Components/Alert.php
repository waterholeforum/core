<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public ?string $type;

    public function __construct(string $type = null)
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('waterhole::components.alert');
    }
}
