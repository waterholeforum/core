<?php

namespace Waterhole\Views\Components\Admin;

use Illuminate\View\Component;
use Waterhole\Models\Structure;

class StructureNode extends Component
{
    public function __construct(public Structure $node)
    {
    }

    public function render()
    {
        return view('waterhole::components.admin.structure-node');
    }
}
