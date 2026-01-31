<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Waterhole\View\Components\Cp\TagRow;
use Waterhole\View\TurboStream;

/**
 * @property int $id
 * @property int $taxonomy_id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Taxonomy $taxonomy
 * @property-read string $edit_url
 */
class Tag extends Model
{
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

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.taxonomies.tags.edit', [
                'taxonomy' => $this->taxonomy_id,
                'tag' => $this,
            ]),
        )->shouldCache();
    }

    /**
     * Get the Turbo Streams that should be sent when this tag is removed.
     */
    public function streamRemoved(): array
    {
        return [TurboStream::remove(new TagRow($this))];
    }
}
