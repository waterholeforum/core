<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class HeaderNotifications extends Component
{
    public function shouldRender()
    {
        return Auth::check();
    }

    public function render()
    {
        return $this->view('waterhole::components.header-notifications');
    }
}
