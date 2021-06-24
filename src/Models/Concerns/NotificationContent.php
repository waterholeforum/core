<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Notification;

/**
 * Trait for models that notifications can be about.
 */
trait NotificationContent
{
    public static function bootNotificationContent(): void
    {
        static::deleted(function (self $model) {
            $model->notifications()->delete();
            $model->groupedNotifications()->delete();
        });
    }

    /**
     * Relationship with the notifications about this model.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'content');
    }

    /**
     * Relationship with the notifications grouped by this model.
     */
    public function groupedNotifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'group');
    }
}
