<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ActionButtons extends Component
{
    public $for;
    public ?array $only;
    public ?array $exclude;
    public array $buttonAttributes;
    public ?string $context;

    public function __construct(
        $for,
        array $only = null,
        array $exclude = null,
        array $buttonAttributes = [],
        string $context = null
    ) {
        $this->for = $for;
        $this->only = $only;
        $this->exclude = $exclude;
        $this->buttonAttributes = $buttonAttributes;
        $this->context = $context;
    }

    public function render()
    {
        return view('waterhole::components.action-buttons');
    }
}
