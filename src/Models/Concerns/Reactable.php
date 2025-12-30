<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\Reaction;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;

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
     * Relationship with the reaction types for this model, including the
     * count for each type and whether the current user has reacted.
     */
    public function reactionCounts(): HasManyThrough
    {
        $relation = $this->hasManyThrough(
            ReactionType::class,
            Reaction::class,
            'content_id',
            'id',
            'id',
            'reaction_type_id',
        )
            ->where('reactions.content_type', $this->getMorphClass())
            ->select('reaction_types.*', 'reactions.content_type', 'reactions.content_id')
            ->selectRaw('count(*) as count')
            ->groupBy('reaction_types.id', 'reactions.content_type', 'reactions.content_id');

        if ($user = Auth::user()) {
            $relation->selectRaw('cast(sum(reactions.user_id = ?) as unsigned) as user_reacted', [
                $user->id,
            ]);
        } else {
            $relation->selectRaw('0 as user_reacted');
        }

        return $relation;
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
