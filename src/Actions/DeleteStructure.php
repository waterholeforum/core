<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Page;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;
use Waterhole\Models\User;

class DeleteStructure extends Action
{
    public bool $destructive = true;

    public function appliesTo($model): bool
    {
        return $model instanceof StructureHeading
            || $model instanceof StructureLink
            || $model instanceof Page;
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

    public function confirm(Collection $models): null|string
    {
        return 'Are you sure you want to delete this?';
    }

    public function run(Collection $models)
    {
        $models->each->delete();
    }
}
