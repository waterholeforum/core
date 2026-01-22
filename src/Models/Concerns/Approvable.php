<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
use Waterhole\Models\Channel;
use Waterhole\Models\User;
use Waterhole\Notifications\ContentApproved;

/**
 * @property bool $is_approved
 */
trait Approvable
{
    use Flaggable;

    protected static function bootApprovable(): void
    {
        static::created(function (self $model) {
            if (!$model->is_approved) {
                $model->flags()->create(['reason' => 'approval']);
            }
        });

        static::updated(function (self $model) {
            if ($model->wasChanged('is_approved') && $model->is_approved) {
                if ($model->user) {
                    $groupsToRemove = $model->user->groups
                        ->filter(
                            fn($group) => ($group->rules['requires_approval'] ?? false) &&
                                ($group->rules['remove_after_approval'] ?? false),
                        )
                        ->pluck('id');

                    $model->user->groups()->detach($groupsToRemove);

                    Notification::send($model->user, new ContentApproved($model));
                }

                if (method_exists($model, 'deliverCreatedEvents')) {
                    $model->deliverCreatedEvents();
                }
            }
        });
    }

    public function initializeApprovable(): void
    {
        $this->is_approved ??= true;

        if (!isset($this->casts['is_approved'])) {
            $this->casts['is_approved'] = 'boolean';
        }
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    protected function applyApprovalVisibility(
        Builder $query,
        ?User $user,
        callable $moderationScope,
    ): void {
        if ($user?->isAdmin()) {
            return;
        }

        $query->where(function (Builder $query) use ($user, $moderationScope) {
            $query->where('is_approved', true);

            if (!$user) {
                return;
            }

            $query->orWhere($query->qualifyColumn('user_id'), $user->id);

            if (!is_null($channelIds = Channel::allPermitted($user, 'moderate'))) {
                $moderationScope($query, $channelIds);
            }
        });
    }
}
