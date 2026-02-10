<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Extend\Core\Actions;

class ActionForm extends Component
{
    public bool $isAuthorized = true;

    public function __construct(
        public $for,
        public ?string $action = null,
        public ?string $return = null,
    ) {
        if ($action) {
            $this->isAuthorized = (bool) resolve(Actions::class)->resolveAction($action, [$for]);
        }
    }

    public function render()
    {
        return $this->view('waterhole::components.action-form');
    }
}
