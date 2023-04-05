<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class HeaderBreadcrumb extends Component
{
    public function render()
    {
        return $this->view('waterhole::components.header-breadcrumb');
    }
}
