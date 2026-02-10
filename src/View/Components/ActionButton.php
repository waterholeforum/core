<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Actions\Action;
use Waterhole\Extend\Core\Actions;

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
        $this->actionable = get_class($for);

        $this->actionInstance = resolve(Actions::class)->resolveAction($action, [$for]);
    }

    public function shouldRender(): bool
    {
        return (bool) $this->actionInstance;
    }

    public function render()
    {
        return $this->view('waterhole::components.action-button');
    }
}
