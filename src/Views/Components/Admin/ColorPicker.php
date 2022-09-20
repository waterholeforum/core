<?php

namespace Waterhole\Views\Components\Admin;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $value = null,
    ) {
    }

    public function render()
    {
        return view('waterhole::components.admin.color-picker');
    }
}
