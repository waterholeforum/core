<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class SidebarMenu extends Component
{
    public function render()
    {
        return view('waterhole::components.responsive-nav');
    }
}
