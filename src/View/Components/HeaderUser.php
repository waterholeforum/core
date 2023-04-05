<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class HeaderUser extends Component
{
    public function render()
    {
        return $this->view('waterhole::components.header-user');
    }
}
