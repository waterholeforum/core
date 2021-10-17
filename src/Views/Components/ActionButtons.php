<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionButtons extends Component
{
    public $for;

    public function __construct($for)
    {
        $this->for = $for;
    }

    public function render()
    {
        return view('waterhole::components.action-buttons');
    }
}
