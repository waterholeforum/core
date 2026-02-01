<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Notification;
use Waterhole\Models\Concerns\NotificationContent;
use Waterhole\Notifications\Reaction as ReactionNotification;

/**
 * @property int $id
 * @property-read ReactionType $reactionType
 * @property-read User $user
 * @property-read Model $content
 */
class Reaction extends Model
{
    use NotificationContent;

    protected static function booted(): void
    {
        static::created(function (self $reaction) {
            $reaction->deliverCreatedNotification();
        });
    }

    protected function deliverCreatedNotification(): void
    {
        $recipient = $this->content?->user;

        if ($recipient?->isNot($this->user)) {
            Notification::send($recipient, new ReactionNotification($this));
        }
    }

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
