<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Cancel extends Component
{
    public function __construct(
        public ?string $default = null
    ) {
    }

    public function render()
    {
        return view('waterhole::components.cancel');
    }
}
