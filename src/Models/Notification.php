<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Waterhole\Notifications\Notification as NotificationTemplate;

class Notification extends DatabaseNotification
{
    protected NotificationTemplate $template;

    public function getTemplateAttribute()
    {
        if (! isset($this->template)) {
            $this->template = new $this->type($this->content);
        }

        return $this->template;
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function content(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeAbout(Builder $query, $model)
    {
        $query
            ->where(function (Builder $query) use ($model) {
                $query->where('subject_type', $model->getMorphClass())
                    ->where('subject_id', $model->getKey());
            })
            ->orWhere(function (Builder $query) use ($model) {
                $query->where('content_type', $model->getMorphClass())
                    ->where('content_id', $model->getKey());
            });

        if ($model instanceof Post) {
            $query->orWhere(function (Builder $query) use ($model) {
                $query->where('subject_type', Comment::class)
                    ->whereIn('subject_id', function ($query) use ($model) {
                        $query->select('id')->from('comments')->where('post_id', $model->getKey());
                    });
            });
        }
    }

    /**
     * Get the latest notification for each distinct subject
     */
    public function scopeGroupBySubject(Builder $query): void
    {
        $sub = static::query()
            ->select('type', 'subject_type', 'subject_id')
            ->selectRaw('MAX(created_at) as created_at')
            ->selectRaw('COUNT(IF(read_at IS NULL, 1, NULL)) as unread_count')
            ->groupBy('type', 'subject_type', 'subject_id');

        $base = $query->getQuery();
        $sub->mergeWheres($base->wheres, $base->bindings['where']);

        $query->joinSub($sub, 'latest_notifications', function (JoinClause $join) {
            $join->on('notifications.type', '=', 'latest_notifications.type')
                ->on('notifications.subject_type', '=', 'latest_notifications.subject_type')
                ->on('notifications.subject_id', '=', 'latest_notifications.subject_id')
                ->on('notifications.created_at', '=', 'latest_notifications.created_at');
        });
    }

    /**
     * Match notifications that have the same type and subject as a notification
     */
    public function scopeGroupedWith(Builder $query, Notification $notification): void
    {
        $query->where($notification->only(['type', 'subject_type', 'subject_id']));
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this
            ->where('id', $value)
            ->whereMorphedTo('notifiable', Auth::user())
            ->firstOrFail();
    }

    // public static function sync(LaravelNotification $notification, Collection $users)
    // {
    //     $data = $notification->toArray(null);
    //
    //     $query = static::query()
    //         ->withTrashed()
    //         ->where('type', get_class($notification));
    //
    //     if ($sender = Arr::pull($data, 'sender')) {
    //         $query->where('sender_id', $sender->getKey());
    //     }
    //
    //     if ($subject = Arr::pull($data, 'subject')) {
    //         $query->where('subject_type', get_class($subject));
    //         $query->where('subject_id', $subject->getKey());
    //     }
    //
    //     if ($content = Arr::pull($data, 'content')) {
    //         $query->where('content_type', get_class($content));
    //         $query->where('content_id', $content->getKey());
    //     }
    //
    //     $notifications = $query->get(['id', 'notifiable_id']);
    //
    //     list($undeleted, $deleted) = $notifications->partition(function ($notification) use ($users) {
    //         return $users->contains($notification->notifiable_id);
    //     });
    //
    //     static::whereIn('id', $undeleted->modelKeys())->restore();
    //     static::whereIn('id', $deleted->modelKeys())->delete();
    //
    //     $users
    //         ->filter(function ($user) use ($notifications) {
    //             return ! $notifications->firstWhere('notifiable_id', $user->id);
    //         })
    //         ->each->notify($notification);
    // }
}
