<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Waterhole\Permissions\PermissionRepository;

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
        $flushCache = fn() => app()->resolved(PermissionRepository::class)
            ? app(PermissionRepository::class)->flush()
            : null;

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
}
