<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Staudenmeir\LaravelCte\Eloquent\QueriesExpressions;
use Waterhole\Notifications\Notification as NotificationTemplate;

/**
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property null|int $sender_id
 * @property null|string $group_type
 * @property null|int $group_id
 * @property null|string $content_type
 * @property null|int $content_id
 * @property null|\Carbon\Carbon $created_at
 * @property null|\Carbon\Carbon $updated_at
 * @property null|\Carbon\Carbon $read_at
 * @property-read null|NotificationTemplate $template
 * @property-read null|User $sender
 * @property-read null|Model $group
 * @property-read null|Model $content
 */
class Notification extends DatabaseNotification
{
    use QueriesExpressions;

    public function getConnectionName()
    {
        return config('waterhole.system.database');
    }

    /**
     * Relationship with the user whose action caused the notification to be
     * sent.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the notification's group.
     */
    public function group(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relationship with the notification's content.
     */
    public function content(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Query notifications that have the same type and group as a notification.
     */
    public function scopeGroupedWith(Builder $query, Notification $notification): void
    {
        if ($notification->group_type && $notification->group_id) {
            $query->where($notification->only(['type', 'group_type', 'group_id']));
        } else {
            $query->whereKey($notification->getKey());
        }
    }

    /**
     * Only allow users to view their own notifications.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->whereKey($value)
            ->whereMorphedTo('notifiable', Auth::user())
            ->firstOrFail();
    }

    protected function template(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->content ? new $this->type($this->content) : null,
        )->shouldCache();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.notifications.show', ['notification' => $this]),
        )->shouldCache();
    }
}
