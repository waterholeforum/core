<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class ValidationErrors extends Component
{
    public ViewErrorBag $errors;

    public function __construct(ViewErrorBag $errors)
    {
        $this->errors = $errors;
    }

    public function render()
    {
        return view('waterhole::components.validation-errors');
    }
}
