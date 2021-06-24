<?php

namespace Waterhole\View\Components\Admin;

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
        return view('waterhole::components.admin.permission-grid');
    }
}
