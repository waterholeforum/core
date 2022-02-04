<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Group;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class EditGroup extends Link
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Group;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('group.edit', $model);
    }

    public function label(Collection $models): string
    {
        return 'Edit';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-pencil';
    }

    public function url(Model $model): string
    {
        return $model->edit_url;
    }
}
