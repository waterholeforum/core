<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Extend\Actionables;
use Waterhole\Models\Model;

class ActionMenu extends Component
{
    public string $url;

    public function __construct(
        Model $for,
        ?string $context = null,
        public array $buttonAttributes = ['class' => 'btn btn--transparent btn--icon text-xs'],
    ) {
        $this->url = route('waterhole.actions.menu', [
            'actionable' => Actionables::getActionableName($for),
            'id' => $for->getKey(),
            'context' => $context,
        ]);
    }

    public function render()
    {
        return $this->view('waterhole::components.action-menu');
    }
}
