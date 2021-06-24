<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Extend\Actions;

class ActionForm extends Component
{
    public ?bool $isAuthorized = null;

    public function __construct(
        public $for,
        public ?string $action = null,
        public ?string $return = null,
    ) {
        if ($action) {
            $this->isAuthorized = $this->isAuthorized($action);
        }
    }

    public function render()
    {
        return view('waterhole::components.action-form');
    }

    public function isAuthorized(string $action = null): bool
    {
        $action ??= $this->action;

        return collect(Actions::for($this->for))->contains(fn($i) => $i instanceof $action);
    }
}
