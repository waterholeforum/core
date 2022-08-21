<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property ?int $sender_id
 * @property ?string $group_type
 * @property ?int $group_id
 * @property ?string $content_type
 * @property ?int $content_id
 * @property ?\Carbon\Carbon $created_at
 * @property ?\Carbon\Carbon $updated_at
 * @property ?\Carbon\Carbon $read_at
 * @property-read ?NotificationTemplate $template
 * @property-read ?User $sender
 * @property-read ?Model $group
 * @property-read ?Model $content
 */
class Notification extends DatabaseNotification
{
    use QueriesExpressions;

    private NotificationTemplate $template;

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

    public function getTemplateAttribute(): ?NotificationTemplate
    {
        if (!$this->content) {
            return null;
        }

        if (!isset($this->template)) {
            $this->template = new $this->type($this->content);
        }

        return $this->template;
    }
}
