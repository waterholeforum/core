<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionMenu extends Component
{
    public $for;
    public array $buttonAttributes;
    public ?array $only;
    public ?array $exclude;

    public function __construct($for, array $only = null, array $exclude = null, array $buttonAttributes = [])
    {
        $this->for = $for;
        $this->only = $only;
        $this->exclude = $exclude;
        $this->buttonAttributes = $buttonAttributes;
    }

    public function render()
    {
        return view('waterhole::components.action-menu');
    }
}
