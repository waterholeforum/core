<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class ResponsiveNav extends Component
{
    public ?NavLink $activeComponent;

    public function __construct(public array $components)
    {
        $this->activeComponent = collect($components)->firstWhere(
            fn($component) => $component instanceof NavLink && $component->isActive(),
        );
    }

    public function render()
    {
        return view('waterhole::components.responsive-nav');
    }
}
