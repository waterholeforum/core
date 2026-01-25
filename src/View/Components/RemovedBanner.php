<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class RemovedBanner extends Component
{
    public function __construct(public Model $subject) {}

    public function render()
    {
        return $this->view('waterhole::components.removed-banner');
    }
}
