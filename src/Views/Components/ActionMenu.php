<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class ActionMenu extends Component
{
    public function __construct(
        public Model $for,
        public ?array $only = null,
        public ?array $exclude = null,
        public array $buttonAttributes = [],
        public string $placement = 'bottom-start',
    ) {
    }

    public function render()
    {
        return view('waterhole::components.action-menu');
    }
}
