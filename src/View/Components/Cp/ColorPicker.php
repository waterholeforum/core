<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $value = null,
    ) {
        $this->value = '#' . ltrim($value, '#');
    }

    public function render()
    {
        return $this->view('waterhole::components.cp.color-picker');
    }
}
