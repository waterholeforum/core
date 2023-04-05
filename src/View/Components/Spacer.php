<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Spacer extends Component
{
    public function render()
    {
        return $this->view('waterhole::components.spacer');
    }
}
