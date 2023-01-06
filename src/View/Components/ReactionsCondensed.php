<?php

namespace Waterhole\View\Components;

use Waterhole\Models\Model;

class ReactionsCondensed extends Reactions
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function shouldRender(): bool
    {
        return (bool) count($this->reactionTypes);
    }

    public function render()
    {
        return count($this->reactionSet->reactionTypes) > 1
            ? view('waterhole::components.reactions-condensed')
            : '<x-waterhole::reactions :model="$model"/>';
    }
}
