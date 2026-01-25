<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;
use Waterhole\Models\Structure;

class StructureNode extends Component
{
    public function __construct(public Structure $node) {}

    public function render()
    {
        return $this->view('waterhole::components.cp.structure-node');
    }
}
