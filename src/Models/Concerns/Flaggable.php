<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Flag;
use Waterhole\Models\User;

trait Flaggable
{
    protected static function bootFlaggable(): void
    {
        static::forceDeleting(function (self $model) {
            $model->pendingFlags()->delete();
        });
    }

    public function flags(): MorphMany
    {
        return $this->morphMany(Flag::class, 'subject');
    }

    public function pendingFlags(): MorphMany
    {
        return $this->flags()->pending();
    }

    abstract public function canModerate(?User $user): bool;

    public function flagUrl(): string
    {
        return $this->url;
    }

    public function resolveFlags(User $moderator): void
    {
        $this->pendingFlags()->update([
            'resolved_at' => now(),
            'resolved_by' => $moderator->getKey(),
        ]);
    }
}
