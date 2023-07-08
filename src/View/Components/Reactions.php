<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Actions\React;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;
use Waterhole\View\Components\Concerns\Streamable;

class Reactions extends Component
{
    use Streamable;

    public Model $model;
    public ?ReactionSet $reactionSet;
    public Collection $reactionTypes;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->reactionSet = $model->reactionSet();

        if (!$this->reactionSet) {
            return;
        }

        $countReactions = fn($reactionType) => $model->reaction_counts[$reactionType->id] ?? 0;

        $this->reactionTypes = $this->reactionSet->reactionTypes->sortByDesc($countReactions);

        if (count($this->reactionTypes) > 1) {
            $this->reactionTypes = $this->reactionTypes->filter($countReactions);
        }
    }

    public function shouldRender()
    {
        return $this->model->reaction_counts ||
            resolve(React::class)->authorize(Auth::user(), $this->model);
    }

    public function render()
    {
        return $this->view('waterhole::components.reactions');
    }
}
