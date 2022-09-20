<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public function __construct(public ?string $title = null, public array $assets = [])
    {
    }

    public function render()
    {
        return view('waterhole::components.layout');
    }
}
