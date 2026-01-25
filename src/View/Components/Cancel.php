<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Cancel extends Component
{
    public function __construct(public ?string $default = null) {}

    public function render()
    {
        return $this->view('waterhole::components.cancel');
    }
}
