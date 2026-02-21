<?php

namespace Waterhole\Extend\Core;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Actions as CoreActions;
use Waterhole\Actions\Action;
use Waterhole\Actions\ActionsCollection;
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
    public function actionsFor(
        $models,
        ?string $context = null,
        ?User $user = null,
    ): ActionsCollection {
        [$models, $list] = $this->modelsAndList($models);

        return (new ActionsCollection(function () use ($list, $models, $user) {
            if (!$list) {
                return;
            }

            foreach ($list->items() as $action) {
                if ($resolved = $this->resolveAction($action, $models, $user)) {
                    yield $resolved;
                }
            }
        }, fn(Action $action) => $this->isRenderable($action, $models, $context)))
            ->remember()
            ->normalizeDividers();
    }

    public function resolveAction($action, $models, ?User $user = null)
    {
        [$models, $list] = $this->modelsAndList($models);

        if (!$list) {
            return null;
        }

        $action = resolve_all([$action])[0];
        $registered = $list->items();

        if (!$this->isRegisteredAction($action, $registered)) {
            return null;
        }

        if (!$action instanceof Action) {
            return $action;
        }

        if ($models->count() > 1 && !$action->bulk) {
            return null;
        }

        $user ??= Auth::user();

        return $models->every(
            fn($model) => $action->appliesTo($model) && $action->authorize($user, $model),
        )
            ? $action
            : null;
    }

    private function modelsAndList($models): array
    {
        $models =
            $models instanceof Collection
                ? $models
                : collect(is_array($models) ? $models : [$models]);

        if ($models->isEmpty()) {
            return [$models, null];
        }

        $modelClass = get_class($models->first());

        return [$models, $this->lists[$modelClass] ?? null];
    }

    private function isRenderable($action, Collection $models, ?string $context): bool
    {
        return !$action instanceof Action || $action->shouldRender($models, $context);
    }

    private function isRegisteredAction($action, array $registered): bool
    {
        foreach ($registered as $class) {
            if ($action instanceof $class) {
                return true;
            }
        }

        return false;
    }

    private function registerDefaults(): void
    {
        $this->for(Models\Channel::class)
            ->add(CoreActions\Follow::class, 'follow')
            ->add(CoreActions\Ignore::class, 'ignore')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\Edit::class, 'edit')
            ->add(CoreActions\DeleteChannel::class, 'delete');

        $this->for(Models\Comment::class)
            ->add(CoreActions\CopyLink::class, 'copy-link')
            ->add(CoreActions\Report::class, 'report')
            ->add(CoreActions\Bookmark::class, 'bookmark')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\Edit::class, 'edit')
            ->add(CoreActions\DismissFlags::class, 'dismiss-flags')
            ->add(CoreActions\RemoveComment::class, 'remove')
            ->add(CoreActions\RestoreComment::class, 'restore')
            ->add(CoreActions\DeleteComment::class, 'delete')
            ->add(CoreActions\MarkAsAnswer::class, 'mark-as-answer')
            ->add(CoreActions\React::class, 'react');

        $this->for(Models\Group::class)
            ->add(CoreActions\Edit::class, 'edit')
            ->add(CoreActions\Delete::class, 'delete');

        $this->for(Models\Page::class)
            ->add(CoreActions\EditStructure::class, 'edit')
            ->add(CoreActions\DeleteStructure::class, 'delete');

        $this->for(Models\Post::class)
            ->add(CoreActions\CopyLink::class, 'copy-link')
            ->add(CoreActions\Report::class, 'report')
            ->add(CoreActions\MarkAsRead::class, 'mark-as-read')
            ->add(CoreActions\Bookmark::class, 'bookmark')
            ->add(CoreActions\Follow::class, 'follow')
            ->add(CoreActions\Ignore::class, 'ignore')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\Edit::class, 'edit')
            ->add(CoreActions\Pin::class, 'pin')
            ->add(CoreActions\Lock::class, 'lock')
            ->add(CoreActions\MoveToChannel::class, 'move')
            ->add(CoreActions\DismissFlags::class, 'dismiss-flags')
            ->add(CoreActions\TrashPost::class, 'trash')
            ->add(CoreActions\RestorePost::class, 'restore')
            ->add(CoreActions\DeletePost::class, 'delete')
            ->add(CoreActions\React::class, 'react');

        $this->for(Models\ReactionSet::class)
            ->add(CoreActions\Edit::class, 'edit')
            ->add(CoreActions\Delete::class, 'delete');

        $this->for(Models\ReactionType::class)
            ->add(CoreActions\EditReactionType::class, 'edit')
            ->add(CoreActions\Delete::class, 'delete');

        $this->for(Models\StructureHeading::class)
            ->add(CoreActions\EditStructure::class, 'edit')
            ->add(CoreActions\DeleteStructure::class, 'delete');

        $this->for(Models\StructureLink::class)
            ->add(CoreActions\EditStructure::class, 'edit')
            ->add(CoreActions\DeleteStructure::class, 'delete');

        $this->for(Models\Tag::class)
            ->add(CoreActions\EditTag::class, 'edit')
            ->add(CoreActions\Delete::class, 'delete');

        $this->for(Models\Taxonomy::class)
            ->add(CoreActions\Edit::class, 'edit')
            ->add(CoreActions\Delete::class, 'delete');

        $this->for(Models\User::class)
            ->add(CoreActions\DeleteSelf::class, 'delete-self')
            ->add(MenuDivider::class, 'divider')
            ->add(CoreActions\Edit::class, 'edit-user')
            ->add(CoreActions\CopyImpersonationUrl::class, 'copy-impersonation-url')
            ->add(CoreActions\SuspendUser::class, 'suspend-user')
            ->add(CoreActions\DeleteUser::class, 'delete-user');
    }
}
