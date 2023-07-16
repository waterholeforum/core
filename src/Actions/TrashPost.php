<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class TrashPost extends Action
{
    public function appliesTo($model): bool
    {
        return $model instanceof Post && !$model->trashed();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('post.delete', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.move-to-trash-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function run(Collection $models)
    {
        $models->each->delete();
    }
}
