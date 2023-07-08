<?php

namespace Waterhole\Models\Concerns;

use App\Models\QuestionAttempt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Waterhole\Models\Reaction;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\User;

/**
 * Methods to manage reactions on a model.
 */
trait Reactable
{
    public static function bootReactable(): void
    {
        static::retrieved(function (Model $model) {
            $model->mergeCasts([
                'reaction_counts' => 'collection',
                'user_reactions' => 'collection',
            ]);
        });
    }

    /**
     * Relationship with the reactions for this model.
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'content');
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

    public function scopeWithReactions(Builder $query): void
    {
        $query->withReactionCounts();
        $query->withUserReactions();
    }

    public function scopeWithReactionCounts(Builder $query): void
    {
        $model = $query->getModel();

        $query->selectSub(
            DB::table(
                Reaction::query()
                    ->selectRaw('reaction_type_id')
                    ->selectRaw('count(*) as count')
                    ->where('content_type', $model->getMorphClass())
                    ->whereColumn('content_id', $model->getQualifiedKeyName())
                    ->groupBy('reaction_type_id')
                    ->getQuery(),
                'a',
            )->selectRaw('JSON_OBJECTAGG(a.reaction_type_id, count)'),
            'reaction_counts',
        );
    }

    public function scopeWithUserReactions(Builder $query, User $user = null): void
    {
        $user ??= Auth::user();

        if (!$user) {
            return;
        }

        $model = $query->getModel();

        $query->selectSub(
            Reaction::query()
                ->selectRaw('JSON_ARRAYAGG(reaction_type_id)')
                ->where('content_type', $model->getMorphClass())
                ->whereColumn('content_id', $model->getQualifiedKeyName())
                ->where('user_id', $user->id)
                ->getQuery(),
            'user_reactions',
        );
    }
}
