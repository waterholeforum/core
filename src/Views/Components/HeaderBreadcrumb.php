<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class HeaderBreadcrumb extends Component
{
    public function render()
    {
        return view('waterhole::components.header-breadcrumb');
    }
}