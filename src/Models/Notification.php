<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Staudenmeir\LaravelCte\Eloquent\QueriesExpressions;
use Waterhole\Notifications\Notification as NotificationTemplate;

class Notification extends DatabaseNotification
{
    use QueriesExpressions;

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

    public function group(): MorphTo
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
                $query->where('group_type', $model->getMorphClass())
                    ->where('group_id', $model->getKey());
            })
            ->orWhere(function (Builder $query) use ($model) {
                $query->where('content_type', $model->getMorphClass())
                    ->where('content_id', $model->getKey());
            });

        if ($model instanceof Post) {
            $query->orWhere(function (Builder $query) use ($model) {
                $query->where('group_type', Comment::class)
                    ->whereIn('group_id', function ($query) use ($model) {
                        $query->select('id')->from('comments')->where('post_id', $model->getKey());
                    });
            });
        }
    }

    /**
     * Match notifications that have the same type and subject as a notification
     */
    public function scopeGroupedWith(Builder $query, Notification $notification): void
    {
        if ($notification->group_type && $notification->group_id) {
            $query->where($notification->only(['type', 'group_type', 'group_id']));
        } else {
            $query->whereKey($notification->getKey());
        }
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this
            ->where('id', $value)
            ->whereMorphedTo('notifiable', Auth::user())
            ->firstOrFail();
    }
}
