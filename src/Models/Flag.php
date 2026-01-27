<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Waterhole\Events\FlagReceived;
use Waterhole\Notifications\NewFlag;
use Waterhole\Models\Support\MorphTypeCache;

/**
 * @property int $id
 * @property string $subject_type
 * @property int $subject_id
 * @property string $reason
 * @property null|int $created_by
 * @property null|int $resolved_by
 * @property null|\Carbon\Carbon $resolved_at
 * @property null|string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Post|Comment $subject
 * @property-read null|User $createdBy
 * @property-read null|User $resolvedBy
 */
class Flag extends Model
{
    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    protected static function booting(): void
    {
        static::addGlobalScope('subjectPresent', function ($query) {
            $query->whereHasMorph(
                'subject',
                MorphTypeCache::forTrait(Concerns\Flaggable::class),
                fn($query) => $query->withoutTrashed(),
            );
        });

        static::addGlobalScope('visible', function ($query) {
            $query->visible(Auth::user());
        });

        static::created(function (self $flag) {
            if (empty(($moderators = $flag->subject->channel->usersWithAbility('moderate')))) {
                return;
            }

            $moderators->each(fn(User $user) => event(new FlagReceived($user)));

            Notification::send($moderators, new NewFlag($flag));
        });
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopePending(Builder $query): void
    {
        $query->whereNull('resolved_at');
    }

    public function scopeVisible(Builder $query, ?User $user): void
    {
        // Remove the default visible global scope which scopes for the
        // currently authenticated user.
        $query->withoutGlobalScope('visible');

        if (is_null($channelIds = Channel::allPermitted($user, 'moderate'))) {
            return;
        }

        if (empty($channelIds)) {
            $query->whereRaw('1 = 0');
            return;
        }

        $query->whereHasMorph(
            'subject',
            '*',
            fn($query) => $query->whereHas('channel', fn($query) => $query->whereKey($channelIds)),
        );
    }
}
