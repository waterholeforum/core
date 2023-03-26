<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\User;

class DeleteReactionSet extends Action
{
    public bool $confirm = true;
    public bool $destructive = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof ReactionSet;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('reaction-set.delete', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.delete-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function confirm(Collection $models): string
    {
        return __('waterhole::cp.delete-reaction-set-confirm-message');
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.delete-confirm-button');
    }

    public function run(Collection $models)
    {
        $models->each->delete();
    }
}
