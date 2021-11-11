<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Waterhole\Models\User;

trait Followable
{
    public function followedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->wherePivot('notifications', 'follow');
    }

    public function scopeFollowing(Builder $query): void
    {
        $query->whereHas('userState', fn($query) => $query->where('notifications', 'follow'));
    }

    protected function setNotifications(?string $value): void
    {
        $this->userState->notifications = $value;
        $this->userState->followed_at = $value === 'follow' ? now() : null;
        $this->userState->save();
    }

    public function follow()
    {
        $this->setNotifications('follow');
    }

    public function unfollow()
    {
        $this->setNotifications(null);
    }

    public function ignore()
    {
        $this->setNotifications('ignore');
    }

    public function unignore()
    {
        $this->setNotifications(null);
    }

    public function isFollowed(): bool
    {
        return $this->userState?->notifications === 'follow';
    }

    public function isIgnored(): bool
    {
        return $this->userState?->notifications === 'ignore';
    }
}
