<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionMenu extends Component
{
    public $for;
    public array $buttonAttributes;

    public function __construct($for, array $buttonAttributes = [])
    {
        $this->for = $for;
        $this->buttonAttributes = $buttonAttributes;
    }

    public function render()
    {
        return view('waterhole::components.action-menu');
    }
}
