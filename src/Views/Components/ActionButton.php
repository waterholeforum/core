<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionButton extends Component
{
    public function __construct(public $for, public string $action, public ?string $return = null)
    {
    }

    public function render()
    {
        return view('waterhole::components.action-button');
    }
}
