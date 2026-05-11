<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;
use Waterhole\View\Components\Concerns\Streamable;

class ReactionsCondensed extends Component
{
    use Streamable;

    public ?ReactionSet $reactionSet;
    public Collection $reactionTypes;
    protected Collection $reactionCounts;

    public function __construct(public Model $model)
    {
        $this->reactionSet = $model->reactionSet();
        $this->reactionTypes = new Collection();
        $this->reactionCounts = $model->reactionCounts->keyBy('id');

        if (!$this->reactionSet?->exists) {
            return;
        }

        $this->reactionTypes = $this->reactionSet->reactionTypes
            ->sortByDesc($this->reactionCount(...))
            ->filter($this->reactionCount(...));
    }

    public function shouldRender(): bool
    {
        return (bool) count($this->reactionTypes);
    }

    public function reactionCount(ReactionType $reactionType): int
    {
        return $this->reactionCounts->get($reactionType->id)?->count ?? 0;
    }

    public function render()
    {
        return $this->view('waterhole::components.reactions-condensed');
    }
}
