<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $scope_type
 * @property int $scope_id
 * @property string $recipient_type
 * @property int $recipient_id
 * @property string $ability
 * @property null|Model $scope
 * @property Model $recipient
 */
class Permission extends Model
{
    public $timestamps = false;

    protected static function booting(): void
    {
        $flushCache = function () {
            Cache::forget('waterhole.permissions');
            app()->forgetInstance('waterhole.permissions');
        };

        static::saved($flushCache);
        static::deleted($flushCache);
    }

    /**
     * Relationship with the model that this permission is for.
     */
    public function scope(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relationship with the user or group that receives this permission.
     */
    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    public function newCollection(array $models = []): PermissionCollection
    {
        return new PermissionCollection($models);
    }
}
