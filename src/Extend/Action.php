<?php

namespace Waterhole\Extend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Actions;
use Waterhole\Extend\Concerns\ManagesComponents;

class Action
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            Actions\React::class,
            Actions\Reply::class,
            Actions\MarkAllAsRead::class,
            Actions\MarkAsRead::class,
            Actions\Follow::class,
            Actions\Unfollow::class,
            Actions\Edit::class,
            Actions\MoveChannel::class,
            Actions\DeleteChannel::class,
            Actions\Delete::class,
        ];
    }

    public static function for($items): array
    {
        if (! Auth::check()) {
            return [];
        }

        if (! $items instanceof Collection) {
            $items = collect($items);
        }

        return collect(static::getComponents())
            ->map(fn($action) => app($action))
            ->when($items->count() > 1, function ($actions) {
                return $actions->filter(fn(Actions\Action $action) => $action->bulk);
            })
            ->filter(fn(Actions\Action $action) => $items->every(fn($item) => $action->appliesTo($item) && $action->authorize(Auth::user(), $item)))
            ->all();
    }
}
