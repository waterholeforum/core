<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;
use Waterhole\View\Components\Concerns\Streamable;

class Reactions extends Component
{
    use Streamable;

    public Model $model;
    public ?ReactionSet $reactionSet;
    public Collection $reactionsByType;
    public Collection $reactionTypes;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->reactionSet = $model->reactionSet();

        if (!$this->reactionSet) {
            return;
        }

        $this->reactionsByType = $model->reactions
            ->loadMissing('user')
            ->groupBy('reaction_type_id');

        $countReactions = fn($reactionType) => isset($this->reactionsByType[$reactionType->id])
            ? $this->reactionsByType[$reactionType->id]->count()
            : 0;

        $this->reactionTypes = $this->reactionSet->reactionTypes->sortBy($countReactions);

        if (count($this->reactionTypes) > 1) {
            $this->reactionTypes = $this->reactionTypes->filter($countReactions);
        }
    }

    public function shouldRender()
    {
        return (bool) $this->reactionSet?->reactionTypes->count();
    }

    public function render()
    {
        return view('waterhole::components.reactions');
    }
}
