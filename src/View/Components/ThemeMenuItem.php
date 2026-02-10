<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class ThemeMenuItem extends Component
{
    public function shouldRender(): bool
    {
        return !config('waterhole.design.theme');
    }

    public function render()
    {
        return $this->view('waterhole::components.theme-menu-item');
    }
}
