<?php

namespace Waterhole\Extend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Actions;
use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\MenuDivider;

/**
 * ```
 * new Extend\Action(MyAction::class)
 * ```
 */
class Action
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            // User actions
            Actions\CopyLink::class,
            Actions\MarkAsRead::class,
            Actions\Follow::class,
            Actions\Unfollow::class,
            Actions\Ignore::class,
            Actions\Unignore::class,

            // Super actions
            Actions\EditComment::class,
            Actions\EditPost::class,
            Actions\MoveChannel::class,
            Actions\DeleteChannel::class,
            Actions\DeleteComment::class,
            Actions\DeletePost::class,

            // Hidden actions
            Actions\Like::class,
        ];
    }

    public static function for($items): array
    {
        if (! $items instanceof Collection) {
            $items = collect(is_array($items) ? $items : [$items]);
        }

        return collect(static::getInstances())
            ->when($items->count() > 1, function ($actions) {
                return $actions->filter(fn($action) => $action->bulk);
            })
            ->filter(fn($action) => $items->every(fn($item) => $action->appliesTo($item) && $action->authorize(Auth::user(), $item)))
            ->values()
            ->all();
    }
}
