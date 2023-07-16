<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Actions\Action;
use Waterhole\Extend;
use Waterhole\Models\Model;

class ActionMenu extends Component
{
    public string $url;

    public function __construct(
        protected Model $for,
        protected ?string $context = null,
        public array $buttonAttributes = ['class' => 'btn btn--transparent btn--icon text-xs'],
    ) {
        $this->url = route('waterhole.actions.menu', [
            'actionable' => Extend\Actionables::getActionableName($for),
            'id' => $for->getKey(),
            'context' => $context,
        ]);
    }

    public function shouldRender(): bool
    {
        $models = collect([$this->for]);

        return collect(Extend\Actions::for($models))
            ->filter(
                fn($action) => $action instanceof Action &&
                    $action->shouldRender($models, $this->context),
            )
            ->isNotEmpty();
    }

    public function render()
    {
        return $this->view('waterhole::components.action-menu');
    }
}
