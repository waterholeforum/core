<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use LogicException;
use Waterhole\Models\Model;
use Waterhole\Models\Structure;

/**
 * Methods for models that can be a part of the forum "structure", like
 * channels, pages, links, and headings.
 *
 * Structurable models are associated with a "node" in the `structure` table,
 * which gives them a position within the structure.
 *
 * @property-read Structure $structure
 */
trait Structurable
{
    public static function bootStructurable(): void
    {
        // When a structurable model is created or deleted, create or delete
        // its corresponding "node" within the structure table.
        static::created(function (Model $model) {
            $model
                ->structure()
                ->create([
                    'position' => Structure::nextPosition(null),
                    ...$model->structureAttributes(),
                ]);
        });

        static::deleting(function (Model $model) {
            if ($model->structure?->children()->withoutGlobalScopes()->exists()) {
                throw new LogicException('Cannot delete a structure node with children.');
            }
        });

        static::deleted(function (Model $model) {
            $model->structure?->delete();
        });
    }

    /**
     * Relationship with the node for this model within the forum structure.
     */
    public function structure(): MorphOne
    {
        return $this->morphOne(Structure::class, 'content')->withoutGlobalScopes();
    }

    protected function structureAttributes(): array
    {
        return [];
    }

    public function permissionScope(string $ability): Model
    {
        return $ability === 'view' ? $this->structure : $this;
    }

    public function isListed(): bool
    {
        return $this->structure->isListed();
    }
}
