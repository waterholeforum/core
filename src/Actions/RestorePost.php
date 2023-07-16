<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class RestorePost extends Action
{
    public function appliesTo($model): bool
    {
        return $model instanceof Post && $model->trashed();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('post.delete', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.restore-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-share-3';
    }

    public function run(Collection $models)
    {
        $models->each->restore();
    }
}
