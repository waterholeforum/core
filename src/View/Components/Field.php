<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Field extends Component
{
    public function __construct(
        public string $name,
        public ?string $label = null,
        public ?string $description = null,
        public ?string $id = null,
    ) {
        $this->id ??= $name;
    }

    public function render()
    {
        return view('waterhole::components.field');
    }
}
