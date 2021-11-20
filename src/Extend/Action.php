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

            MenuDivider::class,

            // Super actions
            Actions\EditComment::class,
            Actions\EditPost::class,
            Actions\Lock::class,
            Actions\Unlock::class,
            Actions\MoveChannel::class,
            Actions\DeleteComment::class,
            Actions\DeletePost::class,

            // Admin actions
            Actions\EditChannel::class,
            Actions\EditStructure::class,
            Actions\EditGroup::class,
            Actions\DeleteChannel::class,
            Actions\DeleteStructure::class,
            Actions\DeleteGroup::class,

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
                return $actions->filter(fn($action) => ! $action instanceof Actions\Action || $action->bulk);
            })
            ->filter(fn($action) => $items->every(fn($item) => ! $action instanceof Actions\Action || ($action->appliesTo($item) && $action->authorize(Auth::user(), $item))))
            ->values()
            ->all();
    }
}
