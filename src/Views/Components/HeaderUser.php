<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class HeaderUser extends Component
{
    public function render()
    {
        return view('waterhole::components.header-user');
    }
}