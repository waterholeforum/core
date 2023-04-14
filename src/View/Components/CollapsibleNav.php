<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class CollapsibleNav extends Component
{
    public ?NavLink $activeComponent;

    public function __construct(
        public array $components,
        public string $buttonClass = '',
        public string $navClass = '',
    ) {
        $this->activeComponent = collect($components)->firstWhere(
            fn($component) => $component instanceof NavLink && $component->isActive,
        );
    }

    public function render()
    {
        return $this->view('waterhole::components.collapsible-nav');
    }
}
