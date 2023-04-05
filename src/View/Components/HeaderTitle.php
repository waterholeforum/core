<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class HeaderTitle extends Component
{
    public function render()
    {
        return $this->view('waterhole::components.header-title');
    }
}
