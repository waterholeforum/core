<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Notification;
use Waterhole\Models\Channel;
use Waterhole\Models\Notification as NotificationModel;
use Waterhole\Models\User;
use Waterhole\Notifications\ContentRemoved;

/**
 * @property null|\Carbon\Carbon $deleted_at
 * @property null|int $deleted_by
 * @property null|string $deleted_reason
 * @property null|string $deleted_message
 * @property-read null|User $deletedBy
 */
trait Deletable
{
    use SoftDeletes;

    protected static function bootDeletable(): void
    {
        static::addGlobalScope(fn($query) => $query->withTrashed());

        static::deleted(function (self $model) {
            if ($model->deleted_by && $model->user && $model->deleted_by !== $model->user_id) {
                Notification::send($model->user, new ContentRemoved($model));
            }
        });

        static::restored(function (self $model) {
            NotificationModel::query()
                ->where('type', ContentRemoved::class)
                ->whereMorphedTo('content', $model)
                ->delete();
        });
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    protected function applyDeletionVisibility(
        Builder $query,
        ?User $user,
        callable $moderationScope,
    ): void {
        if ($user?->isAdmin()) {
            return;
        }

        $query->where(function (Builder $query) use ($user, $moderationScope) {
            $query->whereNull($query->qualifyColumn('deleted_at'));

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
