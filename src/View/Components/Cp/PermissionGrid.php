<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;
use Waterhole\Models\PermissionCollection;

class PermissionGrid extends Component
{
    public function __construct(
        public array $abilities,
        public ?PermissionCollection $permissions,
        public array $defaults = [],
    ) {
    }

    public function render()
    {
        return view('waterhole::components.cp.permission-grid');
    }
}
