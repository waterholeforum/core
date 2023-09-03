<?php

namespace Waterhole\Models\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\Model;
use Waterhole\Models\Reaction;
use Waterhole\Models\ReactionsSummary;

class ReactionsSummaryRelation extends Relation
{
    public function __construct(Model $parent)
    {
        // Reset the table to the model's original as it may have been changed
        // by an adjacency list query.
        $parent->setTable((new $parent())->getTable());

        parent::__construct($parent->newModelQuery(), $parent);
    }

    public function addConstraints(): void
    {
        if (static::$constraints) {
            $this->query->whereKey($this->parent->getKey());
        }

        $sub = Reaction::query()
            ->selectRaw('count(*)')
            ->where('content_type', $this->parent->getMorphClass())
            ->whereColumn('content_id', $this->parent->getQualifiedKeyName())
            ->whereColumn('reaction_type_id', 'reaction_types.id');

        $this->query
            ->crossJoin('reaction_types')
            ->select([
                $this->parent->getQualifiedKeyName() . ' as model_id',
                'reaction_types.id as reaction_type_id',
            ])
            ->selectSub($sub, 'count');

        if ($user = Auth::user()) {
            $this->query->selectSub($sub->clone()->whereBelongsTo($user), 'user_reacted');
        }
    }

    public function addEagerConstraints(array $models): void
    {
        $this->query->whereKey((new Collection($models))->modelKeys());
    }

    public function initRelation(array $models, $relation): array
    {
        foreach ($models as $model) {
            $model->setRelation($relation, null);
        }

        return $models;
    }

    public function match(array $models, Collection $results, $relation): array
    {
        if ($results->isEmpty()) {
            return $models;
        }

        foreach ($models as $model) {
            $model->setRelation(
                $relation,
                new ReactionsSummary($results->where('model_id', $model->getKey())),
            );
        }

        return $models;
    }

    public function getResults(): ReactionsSummary
    {
        return new ReactionsSummary($this->query->get());
    }
}
