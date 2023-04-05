<?php

namespace Waterhole\View\Components;

use Closure;
use Illuminate\View\Component;

class Selector extends Component
{
    public function __construct(
        public array $options,
        public Closure $label,
        public Closure $href,
        public $value = null,
        public string $buttonClass = '',
        public ?string $placeholder = null,
    ) {
    }

    public function render()
    {
        return $this->view('waterhole::components.selector');
    }
}
