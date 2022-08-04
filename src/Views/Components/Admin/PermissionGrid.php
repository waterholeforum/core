<?php

namespace Waterhole\Views\Components\Admin;

use Illuminate\View\Component;
use Waterhole\Models\PermissionCollection;

class PermissionGrid extends Component
{
    public array $abilities;

    public ?PermissionCollection $permissions;

    public array $defaults;

    public function __construct(
        array $abilities,
        ?PermissionCollection $permissions,
        array $defaults = []
    ) {
        $this->abilities = $abilities;
        $this->permissions = $permissions;
        $this->defaults = $defaults;
    }

    public function render()
    {
        return view('waterhole::components.admin.permission-grid');
    }
}
