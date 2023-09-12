<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Reaction;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\Relations\ReactionsSummaryRelation;

/**
 * Methods to manage reactions on a model.
 */
trait Reactable
{
    /**
     * Relationship with the reactions for this model.
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'content');
    }

    /**
     * Relationship with a summary of the reactions for this model.
     *
     * This is less expensive to load than all of the actual reactions.
     */
    public function reactionsSummary(): ReactionsSummaryRelation
    {
        return new ReactionsSummaryRelation($this);
    }

    /**
     * Get the reaction set that applies to this model.
     */
    abstract public function reactionSet(): ?ReactionSet;

    /**
     * Recalculate the score from the reactions.
     */
    public function recalculateScore(): static
    {
        $this->score = $this->reactions()
            ->join('reaction_types', 'reaction_types.id', '=', 'reaction_type_id')
            ->sum('reaction_types.score');

        return $this;
    }
}
