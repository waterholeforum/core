<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

/**
 *  @property null|\Carbon\Carbon $deleted_at
 *  @property null|int $deleted_by
 *  @property null|string $deleted_reason
 * @property-read null|User $deletedBy
 */
trait Deletable
{
    use SoftDeletes;

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    protected function applyDeletionVisibility(
        Builder $query,
        ?User $user,
        callable $moderationScope,
    ): void {
        $query->withTrashed();

        if ($user?->isAdmin()) {
            return;
        }

        $query->where(function (Builder $query) use ($user, $moderationScope) {
            $query->whereNull('deleted_at');

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
