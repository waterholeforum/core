<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;
use Waterhole\Models\Structure;

class StructureCreateMenu extends Component
{
    public function __construct(
        public ?Structure $parent = null,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.cp.structure-create-menu');
    }
}
