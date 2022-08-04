<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionButton extends Component
{
    public $for;

    public string $action;

    public ?string $return;

    public function __construct($for, string $action, string $return = null)
    {
        $this->for = $for;
        $this->action = $action;
        $this->return = $return;
    }

    public function render()
    {
        return view('waterhole::components.action-button');
    }
}
