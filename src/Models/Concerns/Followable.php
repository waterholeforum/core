<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Followable
{
    public function scopeFollowing(Builder $query): void
    {
        $query->whereHas('userState', fn($query) => $query->whereNotNull('followed_at'));
    }

    public function follow(): void
    {
        $this->userState->followed_at = now();
        $this->userState->save();
    }

    public function unfollow(): void
    {
        $this->userState->followed_at = null;
        $this->userState->save();
    }

    public function ignore(): void
    {

    }

    public function isFollowed(): bool
    {
        return (bool) $this->userState->followed_at;
    }

    public function isIgnored(): bool
    {
        return false;
    }
}
