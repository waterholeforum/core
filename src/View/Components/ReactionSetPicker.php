<?php

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Waterhole\Models\ReactionSet;

class ReactionSetPicker extends Component
{
    public Collection $reactionSets;

    public function __construct(public ?string $value = null, public ?ReactionSet $default = null)
    {
        $this->reactionSets = ReactionSet::all();
    }

    public function render()
    {
        return $this->view('waterhole::components.reaction-set-picker');
    }
}
