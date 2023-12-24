<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Page;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;
use Waterhole\Models\User;

class EditStructure extends Link
{
    public function appliesTo($model): bool
    {
        return $model instanceof StructureHeading ||
            $model instanceof StructureLink ||
            $model instanceof Page;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.structure.edit', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.edit-link');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-pencil';
    }

    public function url(Model $model): string
    {
        return $model->edit_url;
    }
}
