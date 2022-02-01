<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class ActionMenu extends Component
{
    public Model $for;
    public array $buttonAttributes;
    public ?array $only;
    public ?array $exclude;
    public string $placement;

    public function __construct(
        Model $for,
        array $only = null,
        array $exclude = null,
        array $buttonAttributes = [],
        string $placement = 'bottom-start'
    ) {
        $this->for = $for;
        $this->only = $only;
        $this->exclude = $exclude;
        $this->buttonAttributes = $buttonAttributes;
        $this->placement = $placement;
    }

    public function render()
    {
        return view('waterhole::components.action-menu');
    }
}
