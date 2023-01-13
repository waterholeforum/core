<?php

namespace Waterhole\Taxonomy;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Waterhole\Models\Model;

class Tag extends Model
{
    protected $with = ['taxonomy'];

    protected static function booted(): void
    {
        static::addGlobalScope(function ($query) {
            $query->whereHas('taxonomy');
        });
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.taxonomies.tags.edit', [
            'taxonomy' => $this->taxonomy,
            'tag' => $this,
        ]);
    }
}
