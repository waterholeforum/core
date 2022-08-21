<?php

namespace Waterhole\Extend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole;
use Waterhole\Actions\Action;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Models\User;

/**
 * The list of Actions.
 *
 * Actions are a mechanism for performing tasks on one or more models – for
 * example, deleting comments, or locking a post. Each item's context menu is
 * really just _this_ list of Actions, filtered so that only the relevant ones
 * are displayed.
 *
 * Use this extender to register a `Waterhole\Actions\Action` class. Plain
 * components such as `MenuDivider`s can also be added.
 */
abstract class Actions
{
    use OrderedList;

    /**
     * Get a list of action instances that can be applied to the given model(s).
     */
    public static function for($models, User $user = null): array
    {
        if (!$models instanceof Collection) {
            $models = collect(is_array($models) ? $models : [$models]);
        }

        $actions = collect(static::build())
            ->values()
            ->map(fn($class) => resolve($class));

        if ($models->count() > 1) {
            $actions = $actions->filter(fn($action) => !$action instanceof Action || $action->bulk);
        }

        return $actions
            ->filter(
                fn($action) => $models->every(
                    fn($model) => !$action instanceof Action ||
                        ($action->appliesTo($model) &&
                            $action->authorize($user ?: Auth::user(), $model)),
                ),
            )
            ->all();
    }
}

// User actions
Actions::add('copy-link', Waterhole\Actions\CopyLink::class);
Actions::add('mark-as-read', Waterhole\Actions\MarkAsRead::class);
Actions::add('follow', Waterhole\Actions\Follow::class);
Actions::add('unfollow', Waterhole\Actions\Unfollow::class);
Actions::add('ignore', Waterhole\Actions\Ignore::class);
Actions::add('unignore', Waterhole\Actions\Unignore::class);

// Divider
Actions::add('divider', Waterhole\Views\Components\MenuDivider::class);

// Super actions
Actions::add('edit-comment', Waterhole\Actions\EditComment::class);
Actions::add('edit-post', Waterhole\Actions\EditPost::class);
Actions::add('lock', Waterhole\Actions\Lock::class);
Actions::add('unlock', Waterhole\Actions\Unlock::class);
Actions::add('move-channel', Waterhole\Actions\MoveChannel::class);
Actions::add('delete-comment', Waterhole\Actions\DeleteComment::class);
Actions::add('delete-post', Waterhole\Actions\DeletePost::class);

// Admin actions
Actions::add('edit-channel', Waterhole\Actions\EditChannel::class);
Actions::add('edit-structure', Waterhole\Actions\EditStructure::class);
Actions::add('edit-group', Waterhole\Actions\EditGroup::class);
Actions::add('edit-user', Waterhole\Actions\EditUser::class);
Actions::add('delete-channel', Waterhole\Actions\DeleteChannel::class);
Actions::add('delete-structure', Waterhole\Actions\DeleteStructure::class);
Actions::add('delete-group', Waterhole\Actions\DeleteGroup::class);
Actions::add('delete-user', Waterhole\Actions\DeleteUser::class);

// Hidden actions
Actions::add('like', Waterhole\Actions\Like::class);
