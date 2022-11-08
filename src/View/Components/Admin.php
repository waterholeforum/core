<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Admin extends Component
{
    public function __construct(public ?string $title = null)
    {
    }

    public function render()
    {
        return view('waterhole::components.admin');
    }
}