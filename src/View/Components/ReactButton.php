<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;

class ReactButton extends Component
{
    public ReactionSet $reactionSet;

    public function __construct(public Model $model)
    {
        $this->reactionSet = ReactionSet::default();
    }

    public function render()
    {
        return view('waterhole::components.react-button');
    }
}
