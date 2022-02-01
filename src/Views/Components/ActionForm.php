<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Extend\Actions;

class ActionForm extends Component
{
    public $for;
    public ?string $action;
    public ?string $return;
    public ?bool $isAuthorized = null;

    public function __construct($for, string $action = null, string $return = null)
    {
        $this->for = $for;
        $this->action = $action;
        $this->return = $return;

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

        return collect(Actions::for($this->for))
            ->contains(fn($i) => $i instanceof $action);
    }
}
