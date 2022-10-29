<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class NavHeading extends Component
{
    public function __construct(public string $heading)
    {
    }

    public function render()
    {
        return <<<'blade'
            <h3 class="nav-heading">{{ $heading }}</h3>
        blade;
    }
}
