<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class HeaderGuest extends Component
{
    public function shouldRender()
    {
        return Auth::guest();
    }

    public function render()
    {
        return $this->view('waterhole::components.header-guest');
    }
}
