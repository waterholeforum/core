<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Waterhole\View\Components\Admin\TagRow;
use Waterhole\View\TurboStream;

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

    /**
     * Get the Turbo Streams that should be sent when this tag is removed.
     */
    public function streamRemoved(): array
    {
        return [TurboStream::remove(new TagRow($this))];
    }
}
