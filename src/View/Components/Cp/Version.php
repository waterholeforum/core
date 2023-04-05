<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Version extends Component
{
    public function render(): View
    {
        return $this->view('waterhole::components.cp.version');
    }
}
