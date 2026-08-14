<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class HeaderSidebar extends Component
{
    public function render()
    {
        return $this->view('waterhole::components.header-sidebar');
    }
}
