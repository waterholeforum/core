<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Icon extends Component
{
    private static View $view;

    public function __construct(public ?string $icon = null)
    {
    }

    public function render()
    {
        return static::$view = view('waterhole::components.icon');
    }
}
