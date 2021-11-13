<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class ValidationErrors extends Component
{
    public function render()
    {
        return view('waterhole::components.validation-errors');
    }
}
