<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionForm extends Component
{
    public $for;
    public ?string $action;
    public ?string $return;

    public function __construct($for, string $action = null, string $return = null)
    {
        $this->for = $for;
        $this->action = $action;
        $this->return = $return;
    }

    public function render()
    {
        return view('waterhole::components.action-form');
    }
}
