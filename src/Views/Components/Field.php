<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Field extends Component
{
    public string $id;

    public function __construct(
        public string $name,
        public ?string $label = null,
        public ?string $description = null,
    ) {
        $this->id = $name;
    }

    public function render()
    {
        return view('waterhole::components.field');
    }
}
