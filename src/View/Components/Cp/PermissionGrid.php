<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class PermissionGrid extends Component
{
    public function __construct(
        public array $abilities,
        public ?Model $scope,
        public array $defaults = [],
    ) {
    }

    public function render()
    {
        return $this->view('waterhole::components.cp.permission-grid');
    }
}
