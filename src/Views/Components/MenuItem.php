<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class MenuItem extends Component
{
    public function __construct(
        public ?bool $active = null,
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $description = null,
        public ?string $href = null,
    ) {
    }

    public function render()
    {
        return view('waterhole::components.menu-item');
    }
}