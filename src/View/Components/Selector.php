<?php

namespace Waterhole\View\Components;

use Closure;
use Illuminate\View\Component;

class Selector extends Component
{
    public function __construct(
        public string $buttonClass = '',
        public $value,
        public array $options,
        public Closure $label,
        public Closure $href,
    ) {
    }

    public function render()
    {
        return view('waterhole::components.selector');
    }
}
