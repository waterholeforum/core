<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Waterhole\Models\Concerns\HasVisibility;

class Flag extends Model
{
    use HasVisibility;

    const UPDATED_AT = null;

    public static function booted()
    {
        static::deleted(function (Flag $flag) {
            Notification::about($flag)->delete();
        });
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
