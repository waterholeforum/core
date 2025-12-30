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

        $user ??= Auth::user();

        $modelClass = get_class($models->first());
        $list = $this->lists[$modelClass] ?? null;

        if (!$list) {
            return [];
        }

        $actions = collect(resolve_all($list->items()));
        $single = $models->count() <= 1;

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

    /**
     * Determine if any action is available for the given model(s).
     */
    public function hasActions($models, ?User $user = null, ?string $context = null): bool
    {
        $models =
            $models instanceof Collection
                ? $models
                : collect(is_array($models) ? $models : [$models]);

        if ($models->isEmpty()) {
            return false;
        }

        $user ??= Auth::user();

        $modelClass = get_class($models->first());
        $list = $this->lists[$modelClass] ?? null;

        if (!$list) {
            return false;
        }

        $single = $models->count() <= 1;

        foreach (resolve_all($list->items()) as $action) {
            if (!$action instanceof Action) {
                continue;
            }

            if (!$single && !$action->bulk) {
                continue;
            }

            if (
                !$models->every(
                    fn($model) => $action->appliesTo($model) && $action->authorize($user, $model),
                )
            ) {
                continue;
            }

            if ($action->shouldRender($models, $context)) {
                return true;
            }
        }

        return false;
    }

    private function registerDefaults(): void
    {
        $this->for(Models\Channel::class)
            ->add('follow', CoreActions\Follow::class)
            ->add('unfollow', CoreActions\Unfollow::class)
            ->add('ignore', CoreActions\Ignore::class)
            ->add('unignore', CoreActions\Unignore::class)
            ->add('divider', MenuDivider::class)
            ->add('edit-channel', CoreActions\EditChannel::class)
            ->add('delete-channel', CoreActions\DeleteChannel::class);

        $this->for(Models\Comment::class)
            ->add('copy-link', CoreActions\CopyLink::class)
            ->add('divider', MenuDivider::class)
            ->add('edit-comment', CoreActions\EditComment::class)
            ->add('hide-comment', CoreActions\HideComment::class)
            ->add('unhide-comment', CoreActions\UnhideComment::class)
            ->add('delete-comment', CoreActions\DeleteComment::class)
            ->add('mark-as-answer', CoreActions\MarkAsAnswer::class)
            ->add('react', CoreActions\React::class);

        $this->for(Models\Group::class)
            ->add('edit-group', CoreActions\EditGroup::class)
            ->add('delete-group', CoreActions\DeleteGroup::class);

        $this->for(Models\Page::class)
            ->add('edit-structure', CoreActions\EditStructure::class)
            ->add('delete-structure', CoreActions\DeleteStructure::class);

        $this->for(Models\Post::class)
            ->add('copy-link', CoreActions\CopyLink::class)
            ->add('mark-as-read', CoreActions\MarkAsRead::class)
            ->add('follow', CoreActions\Follow::class)
            ->add('unfollow', CoreActions\Unfollow::class)
            ->add('ignore', CoreActions\Ignore::class)
            ->add('unignore', CoreActions\Unignore::class)
            ->add('divider', MenuDivider::class)
            ->add('edit-post', CoreActions\EditPost::class)
            ->add('pin', CoreActions\Pin::class)
            ->add('unpin', CoreActions\Unpin::class)
            ->add('lock', CoreActions\Lock::class)
            ->add('unlock', CoreActions\Unlock::class)
            ->add('move-to-channel', CoreActions\MoveToChannel::class)
            ->add('restore-post', CoreActions\RestorePost::class)
            ->add('trash-post', CoreActions\TrashPost::class)
            ->add('delete-post', CoreActions\DeletePost::class)
            ->add('react', CoreActions\React::class);

        $this->for(Models\ReactionSet::class)
            ->add('edit-reaction-set', CoreActions\EditReactionSet::class)
            ->add('delete-reaction-set', CoreActions\DeleteReactionSet::class);

        $this->for(Models\ReactionType::class)
            ->add('edit-reaction-type', CoreActions\EditReactionType::class)
            ->add('delete-reaction-type', CoreActions\DeleteReactionType::class);

        $this->for(Models\StructureHeading::class)
            ->add('edit-structure', CoreActions\EditStructure::class)
            ->add('delete-structure', CoreActions\DeleteStructure::class);

        $this->for(Models\StructureLink::class)
            ->add('edit-structure', CoreActions\EditStructure::class)
            ->add('delete-structure', CoreActions\DeleteStructure::class);

        $this->for(Models\Tag::class)
            ->add('edit-tag', CoreActions\EditTag::class)
            ->add('delete-tag', CoreActions\DeleteTag::class);

        $this->for(Models\Taxonomy::class)
            ->add('edit-taxonomy', CoreActions\EditTaxonomy::class)
            ->add('delete-taxonomy', CoreActions\DeleteTaxonomy::class);

        $this->for(Models\User::class)
            ->add('delete-self', CoreActions\DeleteSelf::class)
            ->add('divider', MenuDivider::class)
            ->add('edit-user', CoreActions\EditUser::class)
            ->add('copy-impersonation-url', CoreActions\CopyImpersonationUrl::class)
            ->add('suspend-user', CoreActions\SuspendUser::class)
            ->add('delete-user', CoreActions\DeleteUser::class);
    }
}
