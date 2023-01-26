<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property-read ReactionType $reactionType
 * @property-read User $user
 * @property-read Model $content
 */
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
