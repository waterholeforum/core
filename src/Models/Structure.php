<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $position
 * @property string $content_type
 * @property int $content_id
 * @property-read Model $content
 */
class Structure extends Model
{
    protected $table = 'structure';

    public $timestamps = false;

    public function content(): MorphTo
    {
        return $this->morphTo();
    }
}
