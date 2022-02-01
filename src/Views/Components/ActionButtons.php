<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Actions\Action;
use Waterhole\Extend\Actionables;
use Waterhole\Extend\Actions;
use Waterhole\Models\Model;

class ActionButtons extends Component
{
    public Model $for;
    public array $buttonAttributes;
    public string $actionable;
    public array $actions;

    public function __construct(
        Model $for,
        array $only = null,
        array $exclude = null,
        array $buttonAttributes = []
    ) {
        $this->for = $for;
        $this->buttonAttributes = $buttonAttributes;
        $this->actionable = Actionables::getActionableName($for);

        $actions = collect(Actions::for($for));

        if (isset($only)) {
            $actions = $actions->filter(fn($action) => in_array(get_class($action), $only));
        }

        if (isset($exclude)) {
            $actions = $actions->filter(fn($action) => ! in_array(get_class($action), $exclude));
        }

        $this->actions = $actions
            ->filter(fn($action) => ! $action instanceof Action || $action->shouldRender(collect([$for])))
            ->values()
            ->all();
    }

    public function render()
    {
        return view('waterhole::components.action-buttons');
    }

    public function shouldRender(): bool
    {
        return ! empty($this->actions);
    }
}
