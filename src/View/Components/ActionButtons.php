<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Actions\Action;
use Waterhole\Extend\Core\Actions;
use Waterhole\Models\Model;

class ActionButtons extends Component
{
    public string $actionable;
    public Collection $actions;

    public function __construct(
        public Model $for,
        array $only = null,
        array $exclude = null,
        public ?int $limit = null,
        public ?string $context = null,
    ) {
        $this->actionable = get_class($for);

        $actions = collect(resolve(Actions::class)->actionsFor($for));

        if (isset($only)) {
            $actions = $actions->filter(fn($action) => in_array(get_class($action), $only));
        }

        if (isset($exclude)) {
            $actions = $actions->reject(fn($action) => in_array(get_class($action), $exclude));
        }

        $models = collect([$for]);

        $this->actions = $actions
            ->filter(
                fn($action) => !$action instanceof Action ||
                    $action->shouldRender($models, $context),
            )
            ->values()
            ->reject(fn($action, $i) => $action instanceof MenuDivider && $i === 0);
    }

    public function render()
    {
        return $this->view('waterhole::components.action-buttons');
    }

    public function shouldRender(): bool
    {
        return $this->actions->isNotEmpty();
    }
}
