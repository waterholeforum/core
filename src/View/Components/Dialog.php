<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Dialog extends Component
{
    public function __construct(public ?string $title = null) {}

    public function render()
    {
        return $this->view('waterhole::components.dialog');
    }
}
