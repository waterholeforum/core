<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionMenu extends Component
{
    public $for;
    public array $buttonAttributes;
    public ?array $only;
    public ?array $exclude;
    public string $placement;
    public ?string $context;

    public function __construct(
        $for,
        array $only = null,
        array $exclude = null,
        array $buttonAttributes = [],
        string $placement = 'bottom-start',
        string $context = null
    ) {
        $this->for = $for;
        $this->only = $only;
        $this->exclude = $exclude;
        $this->buttonAttributes = $buttonAttributes;
        $this->placement = $placement;
        $this->context = $context;
    }

    public function render()
    {
        return view('waterhole::components.action-menu');
    }
}
