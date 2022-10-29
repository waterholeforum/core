<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class ThemeSelector extends Component
{
    public function shouldRender(): bool
    {
        return config('waterhole.design.support_dark_mode');
    }

    public function render()
    {
        return view('waterhole::components.theme-selector');
    }
}
