<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Actions\Action;
use Waterhole\Extend\Actionables;
use Waterhole\Extend\Actions;
use Waterhole\Models\Model;

class ActionButtons extends Component
{
    public string $actionable;
    public array $actions;

    public function __construct(
        public Model $for,
        array $only = null,
        array $exclude = null,
        public array $buttonAttributes = [],
    ) {
        $this->actionable = Actionables::getActionableName($for);

        $actions = collect(Actions::for($for));

        if (isset($only)) {
            $actions = $actions->filter(fn($action) => in_array(get_class($action), $only));
        }

        if (isset($exclude)) {
            $actions = $actions->filter(fn($action) => !in_array(get_class($action), $exclude));
        }

        $this->actions = $actions
            ->filter(
                fn($action) => !$action instanceof Action || $action->shouldRender(collect([$for])),
            )
            ->values()
            ->reject(fn($action, $i) => $action instanceof MenuDivider && $i === 0)
            ->all();
    }

    public function render()
    {
        return view('waterhole::components.action-buttons');
    }

    public function shouldRender(): bool
    {
        return !empty($this->actions);
    }
}