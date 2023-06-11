<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Actions\Action;
use Waterhole\Extend\Actionables;
use Waterhole\Extend\Actions;

class ActionButton extends Component
{
    public ?string $actionable;
    public ?Action $actionInstance;

    public function __construct(
        public $for,
        public string $action,
        public ?string $return = null,
        public bool $icon = false,
        public array $formAttributes = [],
    ) {
        $this->actionable = Actionables::getActionableName($for);

        $this->actionInstance = collect(Actions::for([$for]))
            ->filter(fn($a) => $a instanceof $action)
            ->first();
    }

    public function shouldRender()
    {
        return (bool) $this->actionInstance;
    }

    public function render()
    {
        return $this->view('waterhole::components.action-button');
    }
}
