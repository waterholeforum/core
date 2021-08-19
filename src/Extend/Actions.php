<?php

namespace Waterhole\Extend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Actions\Action;
use Waterhole\Actions\Delete;
use Waterhole\Actions\DeleteChannel;
use Waterhole\Actions\Edit;
use Waterhole\Actions\Like;
use Waterhole\Actions\MoveChannel;
use Waterhole\Actions\Pin;
use Waterhole\Actions\Reply;
use Waterhole\Actions\Unpin;
use Waterhole\Extend\Concerns\ManagesComponents;

class Actions
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            Like::class,
            Reply::class,
            Pin::class,
            Unpin::class,
            Edit::class,
            MoveChannel::class,
            DeleteChannel::class,
            Delete::class,
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
                return $actions->filter(fn(Action $action) => $action->bulk);
            })
            ->filter(fn(Action $action) => $items->every(fn($item) => $action->appliesTo($item) && $action->authorize(Auth::user(), $item)))
            ->all();
    }
}
