<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
            $model->structure()->create([
                'position' => ($pos = Structure::max('position')) ? $pos + 1 : 0,
            ]);
        });

        static::deleted(function (Model $model) {
            $model->structure()->delete();
        });
    }

    /**
     * Relationship with the node for this model within the forum structure.
     */
    public function structure(): MorphOne
    {
        return $this->morphOne(Structure::class, 'content');
    }
}
