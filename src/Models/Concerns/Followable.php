<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Waterhole\Models\User;

/**
 * Methods to make a model "followable" per user.
 *
 * A followable model can be "followed" or "ignored" by each user, which will
 * determine how they see and are notified about activity in the model. The
 * model must have user state (see `HasUserState`) with `notification` and
 * `followed_at` columns to store this state.
 */
trait Followable
{
    /**
     * Relationship with all users who are following this model.
     */
    public function followedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->wherePivot('notifications', 'follow');
    }

    /**
     * Relationship with all users who are ignoring this model.
     */
    public function ignoredBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->wherePivot('notifications', 'ignore');
    }

    /**
     * Find only models that the current user is following.
     */
    public function scopeFollowing(Builder $query): void
    {
        $query->whereHas('userState', fn($query) => $query->where('notifications', 'follow'));
    }

    /**
     * Save the current user's notification preference for this model.
     */
    protected function setNotifications(?string $value): void
    {
        if ($this->userState->notifications !== $value) {
            $this->userState->notifications = $value;
            $this->userState->followed_at = $value === 'follow' ? now() : null;
            $this->userState->save();
        }
    }

    /**
     * Follow this model for the current user.
     */
    public function follow(): void
    {
        $this->setNotifications('follow');
    }

    /**
     * Unfollow this model for the current user.
     */
    public function unfollow(): void
    {
        $this->setNotifications(null);
    }

    /**
     * Ignore this model for the current user.
     */
    public function ignore(): void
    {
        $this->setNotifications('ignore');
    }

    /**
     * Unignore this model for the current user.
     */
    public function unignore(): void
    {
        $this->setNotifications(null);
    }

    /**
     * Whether the current user is following this model.
     */
    public function isFollowed(): bool
    {
        return $this->userState?->notifications === 'follow';
    }

    /**
     * Whether the current user is ignoring this model.
     */
    public function isIgnored(): bool
    {
        return $this->userState?->notifications === 'ignore';
    }
}
