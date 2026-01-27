<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Waterhole\Models\Support\MorphTypeCache;

/**
 * @property int $id
 * @property int $position
 * @property string $content_type
 * @property int $content_id
 * @property bool $is_listed
 * @property-read Model $content
 */
class Structure extends Model
{
    protected $table = 'structure';

    public $timestamps = false;

    protected $casts = [
        'is_listed' => 'bool',
    ];

    protected static function booting()
    {
        static::addGlobalScope('hasVisibleContent', function ($query) {
            $query->whereHasMorph(
                'content',
                MorphTypeCache::forTrait(Concerns\Structurable::class),
            );
        });
    }

    public function content(): MorphTo
    {
        return $this->morphTo();
    }
}
