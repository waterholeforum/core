<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Actions\React;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;
use Waterhole\View\Components\Concerns\Streamable;

class Reactions extends Component
{
    use Streamable;

    public Model $model;
    public ?ReactionSet $reactionSet;
    public bool $isAuthorized;
    public Collection $reactionTypes;
    protected Collection $reactionCounts;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->reactionSet = $model->reactionSet();
        $this->isAuthorized = resolve(React::class)->authorize(Auth::user(), $this->model);
        $this->reactionTypes = new Collection();
        $this->reactionCounts = $model->reactionCounts->keyBy('id');

        if (!$this->reactionSet?->exists) {
            return;
        }

        $this->reactionTypes = $this->reactionSet->reactionTypes->sortByDesc(
            $this->reactionCount(...),
        );

        if (count($this->reactionTypes) > 1) {
            $this->reactionTypes = $this->reactionTypes->filter($this->reactionCount(...));
        }
    }

    public function shouldRender(): bool
    {
        return $this->reactionSet?->exists && ($this->model->reactionCounts || $this->isAuthorized);
    }

    public function reactionCount(ReactionType $reactionType): int
    {
        return $this->reactionCounts->get($reactionType->id)?->count ?? 0;
    }

    public function userReacted(ReactionType $reactionType): bool
    {
        return (bool) $this->reactionCounts->get($reactionType->id)?->user_reacted;
    }

    public function render()
    {
        return $this->view('waterhole::components.reactions');
    }
}
