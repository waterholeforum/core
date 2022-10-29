<?php

namespace Waterhole\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Version extends Component
{
    public function render(): View
    {
        return view('waterhole::components.admin.version');
    }
}
