<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $value = null,
        public ?string $placeholder = null,
    ) {
        $this->value = $value ? '#' . ltrim($value, '#') : null;
    }

    public function render()
    {
        return $this->view('waterhole::components.cp.color-picker');
    }
}
