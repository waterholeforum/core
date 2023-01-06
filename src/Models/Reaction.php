<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactionType(): BelongsTo
    {
        return $this->belongsTo(ReactionType::class);
    }

    public function content(): MorphTo
    {
        return $this->morphTo();
    }
}
