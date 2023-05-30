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

    private static Collection $instances;

    /**
     * Get a list of action instances that can be applied to the given model(s).
     */
    public static function for($models, User $user = null): array
    {
        if (!$models instanceof Collection) {
            $models = collect(is_array($models) ? $models : [$models]);
        }

        $actions = static::$instances ??= collect(static::build())
            ->values()
            ->map(fn($class) => resolve($class));

        $single = $models->count() <= 1;
        $user ??= Auth::user();

        return $actions
            ->filter(
                fn($action) => !$action instanceof Action ||
                    (($single || $action->bulk) &&
                        $models->every(
                            fn($model) => $action->appliesTo($model) &&
                                $action->authorize($user, $model),
                        )),
            )
            ->all();
    }
}

// User actions
Actions::add(Waterhole\Actions\CopyLink::class, 0, 'copy-link');
Actions::add(Waterhole\Actions\MarkAsRead::class, 0, 'mark-as-read');
Actions::add(Waterhole\Actions\MarkAsUnread::class, 0, 'mark-as-unread');
Actions::add(Waterhole\Actions\Follow::class, 0, 'follow');
Actions::add(Waterhole\Actions\Unfollow::class, 0, 'unfollow');
Actions::add(Waterhole\Actions\Ignore::class, 0, 'ignore');
Actions::add(Waterhole\Actions\Unignore::class, 0, 'unignore');
Actions::add(Waterhole\Actions\DeleteSelf::class, 0, 'delete-self');

// Divider
Actions::add(Waterhole\View\Components\MenuDivider::class, 0, 'divider');

// Super actions
Actions::add(Waterhole\Actions\EditComment::class, 0, 'edit-comment');
Actions::add(Waterhole\Actions\EditPost::class, 0, 'edit-post');
Actions::add(Waterhole\Actions\Lock::class, 0, 'lock');
Actions::add(Waterhole\Actions\Unlock::class, 0, 'unlock');
Actions::add(Waterhole\Actions\MoveToChannel::class, 0, 'move-to-channel');
Actions::add(Waterhole\Actions\DeleteComment::class, 0, 'delete-comment');
Actions::add(Waterhole\Actions\DeletePost::class, 0, 'delete-post');

// Admin actions
Actions::add(Waterhole\Actions\EditChannel::class, 0, 'edit-channel');
Actions::add(Waterhole\Actions\EditStructure::class, 0, 'edit-structure');
Actions::add(Waterhole\Actions\EditGroup::class, 0, 'edit-group');
Actions::add(Waterhole\Actions\EditUser::class, 0, 'edit-user');
Actions::add(Waterhole\Actions\EditReactionSet::class, 0, 'edit-reaction-set');
Actions::add(Waterhole\Actions\EditReactionType::class, 0, 'edit-reaction-type');
Actions::add(Waterhole\Actions\EditTag::class, 0, 'edit-tag');
Actions::add(Waterhole\Actions\EditTaxonomy::class, 0, 'edit-taxonomy');

Actions::add(Waterhole\Actions\CopyImpersonationUrl::class, 0, 'copy-impersonation-url');
Actions::add(Waterhole\Actions\SuspendUser::class, 0, 'suspend-user');

Actions::add(Waterhole\Actions\DeleteChannel::class, 0, 'delete-channel');
Actions::add(Waterhole\Actions\DeleteStructure::class, 0, 'delete-structure');
Actions::add(Waterhole\Actions\DeleteGroup::class, 0, 'delete-group');
Actions::add(Waterhole\Actions\DeleteUser::class, 0, 'delete-user');
Actions::add(Waterhole\Actions\DeleteReactionSet::class, 0, 'delete-reaction-set');
Actions::add(Waterhole\Actions\DeleteReactionType::class, 0, 'delete-reaction-type');
Actions::add(Waterhole\Actions\DeleteTag::class, 0, 'delete-tag');
Actions::add(Waterhole\Actions\DeleteTaxonomy::class, 0, 'delete-taxonomy');

// Hidden actions
Actions::add(Waterhole\Actions\MarkAsAnswer::class, 0, 'mark-as-answer');
Actions::add(Waterhole\Actions\React::class, 0, 'react');
