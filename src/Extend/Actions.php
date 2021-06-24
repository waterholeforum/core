<?php

namespace Waterhole\Extend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Actions\Action;
use Waterhole\Actions\Delete;
use Waterhole\Actions\DeleteChannel;
use Waterhole\Actions\MoveChannel;
use Waterhole\Actions\Pin;
use Waterhole\Actions\Unpin;
use Waterhole\Extend\Concerns\ManagesComponents;

class Actions
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            Pin::class => 0,
            Unpin::class => 0,
            MoveChannel::class => 0,
            DeleteChannel::class => 100,
            Delete::class => 100,
        ];
    }

    public static function for($items)
    {
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
