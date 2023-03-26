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
    public bool $confirm = true;
    public bool $destructive = true;

    public function appliesTo($model): bool
    {
        return $model instanceof StructureHeading ||
            $model instanceof StructureLink ||
            $model instanceof Page;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('structure.delete', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.delete-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function confirm(Collection $models): null|string
    {
        return __('waterhole::cp.delete-structure-confirm-message');
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
