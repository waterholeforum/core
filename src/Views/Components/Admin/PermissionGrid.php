<?php

namespace Waterhole\Views\Components\Admin;

use Illuminate\View\Component;
use Waterhole\Models\PermissionCollection;

class PermissionGrid extends Component
{
    public array $abilities;
    public ?PermissionCollection $permissions;
    public ?PermissionCollection $parentPermissions;
    public array $defaults;

    public function __construct(
        array $abilities,
        ?PermissionCollection $permissions,
        ?PermissionCollection $parentPermissions,
        array $defaults = []
    ) {
        $this->abilities = $abilities;
        $this->permissions = $permissions;
        $this->parentPermissions = $parentPermissions;
        $this->defaults = $defaults;
    }

    public function render()
    {
        return view('waterhole::components.admin.permission-grid');
    }
}
