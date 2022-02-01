<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Group;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class DeleteGroup extends Action
{
    public bool $destructive = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof Group;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('delete', $model);
    }

    public function label(Collection $models): string
    {
        return 'Delete...';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-trash';
    }

    public function confirm(Collection $models): string
    {
        return 'Are you sure you want to delete this group?';
    }

    public function run(Collection $models)
    {
        $models->each->delete();
    }
}
