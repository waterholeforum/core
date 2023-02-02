<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionType;
use Waterhole\Models\User;

class DeleteReactionType extends Action
{
    public bool $confirm = true;

    public bool $destructive = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof ReactionType;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('reaction-type.delete', $model);
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
        return __('waterhole::admin.delete-reaction-type-confirm-message');
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
