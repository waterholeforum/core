<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class IndexFooterTheme extends Component
{
    public function shouldRender(): bool
    {
        return config('waterhole.design.support_dark_mode');
    }

    public function render()
    {
        return view('waterhole::components.index-footer-theme');
    }
}
