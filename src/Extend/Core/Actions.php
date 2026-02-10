<?php

namespace Waterhole\Extend\Core;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Actions as CoreActions;
use Waterhole\Actions\Action;
use Waterhole\Extend\Support\OrderedList;
use Waterhole\Models;
use Waterhole\Models\User;
use Waterhole\View\Components\MenuDivider;
use function Waterhole\resolve_all;

/**
 * Action lists per model class.
 *
 * Resolves authorized actions for menus and buttons using model class names.
 */
class Actions
{
    /**
     * @var array<class-string, OrderedList>
     */
    private array $lists = [];

    public function __construct()
    {
        $this->registerDefaults();
    }

    public function for(string $modelClass): OrderedList
    {
        return $this->lists[$modelClass] ??= new OrderedList();
    }

    public function hasList(string $modelClass): bool
    {
        return isset($this->lists[$modelClass]);
    }

    /**
     * Get a list of action instances that can be applied to the given model(s).
     */
    public function actionsFor($models, ?User $user = null): array
    {
        $models =
            $models instanceof Collection
                ? $models
                : collect(is_array($models) ? $models : [$models]);

        if ($models->isEmpty()) {
            return [];
        }

        $modelClass = get_class($models->first());
        $list = $this->lists[$modelClass] ?? null;

        if (!$list) {
            return [];
        }

        return collect($list->items())
            ->map(fn($action) => $this->resolveAction($action, $models, $user))
            ->filter()
            ->all();
    }

    /**
     * Determine if there are any actions that should render for the models.
     */
    public function hasRenderableActionsFor(
        $models,
        ?string $context = null,
        ?User $user = null,
    ): bool {
        $models =
            $models instanceof Collection
                ? $models
                : collect(is_array($models) ? $models : [$models]);

        if ($models->isEmpty()) {
            return false;
        }

        $modelClass = get_class($models->first());
        $list = $this->lists[$modelClass] ?? null;

        if (!$list) {
            return false;
        }

        foreach ($list->items() as $action) {
            $action = $this->resolveAction($action, $models, $user);

            if ($action instanceof Action) {
                if ($action->shouldRender($models, $context)) {
                    return true;
                }
            } elseif ($action && !$action instanceof MenuDivider) {
                return true;
            }
        }

        return false;
    }

    public function resolveAction($action, $models, ?User $user = null)
    {
        $models = collect($models);

        if ($models->isEmpty()) {
            return null;
        }

        $modelClass = get_class($models->first());
        $available = $this->lists[$modelClass]?->items() ?? null;

        if (!$available) {
            return null;
        }

        $action = resolve_all([$action])[0];

        if (!collect($available)->some(fn($registered) => $action instanceof $registered)) {
            return null;
        }

        if (!$action instanceof Action) {
            return $action;
        }

        if ($models->count() > 1 && !$action->bulk) {
            return null;
        }

        $user ??= Auth::user();

        if (
            !$models->every(
                fn($model) => $action->appliesTo($model) && $action->authorize($user, $model),
            )
        ) {
            return null;
        }

        return $action;
    }

    private function registerDefaults(): void
    {
        $this->for(Models\Channel::class)
            ->add(CoreActions\Follow::class, 'follow')
            ->add(CoreActions\Unfollow::class, 'unfollow')
            ->add(CoreActions\Ignore::class, 'ignore')
            ->add(CoreActions\Unignore::class, 'unignore')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\EditChannel::class, 'edit-channel')
            ->add(CoreActions\DeleteChannel::class, 'delete-channel');

        $this->for(Models\Comment::class)
            ->add(CoreActions\CopyLink::class, 'copy-link')
            ->add(CoreActions\Report::class, 'report')
            ->add(CoreActions\Bookmark::class, 'bookmark')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\EditComment::class, 'edit-comment')
            ->add(CoreActions\DismissFlags::class, 'dismiss-flags')
            ->add(CoreActions\RemoveComment::class, 'remove-comment')
            ->add(CoreActions\RestoreComment::class, 'restore-comment')
            ->add(CoreActions\DeleteComment::class, 'delete-comment')
            ->add(CoreActions\MarkAsAnswer::class, 'mark-as-answer')
            ->add(CoreActions\React::class, 'react');

        $this->for(Models\Group::class)
            ->add(CoreActions\EditGroup::class, 'edit-group')
            ->add(CoreActions\DeleteGroup::class, 'delete-group');

        $this->for(Models\Page::class)
            ->add(CoreActions\EditStructure::class, 'edit-structure')
            ->add(CoreActions\DeleteStructure::class, 'delete-structure');

        $this->for(Models\Post::class)
            ->add(CoreActions\CopyLink::class, 'copy-link')
            ->add(CoreActions\Report::class, 'report')
            ->add(CoreActions\MarkAsRead::class, 'mark-as-read')
            ->add(CoreActions\Bookmark::class, 'bookmark')
            ->add(CoreActions\Follow::class, 'follow')
            ->add(CoreActions\Unfollow::class, 'unfollow')
            ->add(CoreActions\Ignore::class, 'ignore')
            ->add(CoreActions\Unignore::class, 'unignore')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\EditPost::class, 'edit-post')
            ->add(CoreActions\Pin::class, 'pin')
            ->add(CoreActions\Unpin::class, 'unpin')
            ->add(CoreActions\Lock::class, 'lock')
            ->add(CoreActions\Unlock::class, 'unlock')
            ->add(CoreActions\MoveToChannel::class, 'move-to-channel')
            ->add(CoreActions\DismissFlags::class, 'dismiss-flags')
            ->add(CoreActions\TrashPost::class, 'trash-post')
            ->add(CoreActions\RestorePost::class, 'restore-post')
            ->add(CoreActions\DeletePost::class, 'delete-post')
            ->add(CoreActions\React::class, 'react');

        $this->for(Models\ReactionSet::class)
            ->add(CoreActions\EditReactionSet::class, 'edit-reaction-set')
            ->add(CoreActions\DeleteReactionSet::class, 'delete-reaction-set');

        $this->for(Models\ReactionType::class)
            ->add(CoreActions\EditReactionType::class, 'edit-reaction-type')
            ->add(CoreActions\DeleteReactionType::class, 'delete-reaction-type');

        $this->for(Models\StructureHeading::class)
            ->add(CoreActions\EditStructure::class, 'edit-structure')
            ->add(CoreActions\DeleteStructure::class, 'delete-structure');

        $this->for(Models\StructureLink::class)
            ->add(CoreActions\EditStructure::class, 'edit-structure')
            ->add(CoreActions\DeleteStructure::class, 'delete-structure');

        $this->for(Models\Tag::class)
            ->add(CoreActions\EditTag::class, 'edit-tag')
            ->add(CoreActions\DeleteTag::class, 'delete-tag');

        $this->for(Models\Taxonomy::class)
            ->add(CoreActions\EditTaxonomy::class, 'edit-taxonomy')
            ->add(CoreActions\DeleteTaxonomy::class, 'delete-taxonomy');

        $this->for(Models\User::class)
            ->add(CoreActions\DeleteSelf::class, 'delete-self')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\EditUser::class, 'edit-user')
            ->add(CoreActions\CopyImpersonationUrl::class, 'copy-impersonation-url')
            ->add(CoreActions\SuspendUser::class, 'suspend-user')
            ->add(CoreActions\DeleteUser::class, 'delete-user');
    }
}
