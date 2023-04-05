<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class IndexFooter extends Component
{
    public function render()
    {
        return $this->view('waterhole::components.index-footer');
    }
}
