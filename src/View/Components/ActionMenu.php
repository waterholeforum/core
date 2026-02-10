<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Extend\Core\Actions;
use Waterhole\Models\Model;

class ActionMenu extends Component
{
    public string $url;
    protected bool $hasRenderableActions;

    public function __construct(
        protected Model $for,
        protected ?string $context = null,
        public array $buttonAttributes = ['class' => 'btn btn--transparent btn--icon text-xs'],
    ) {
        $return = request('return', request()->fullUrl());

        $this->url = route('waterhole.actions.menu', [
            'actionable' => get_class($for),
            'id' => $for->getKey(),
            'context' => $context,
            'return' => $return,
        ]);
    }

    public function render()
    {
        return $this->view('waterhole::components.action-menu');
    }

    public function shouldRender(): bool
    {
        return resolve(Actions::class)->hasRenderableActionsFor($this->for, $this->context);
    }
}
