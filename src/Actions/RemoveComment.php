<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Actions\Concerns\RemovesContent;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class RemoveComment extends Action
{
    use RemovesContent;

    public function appliesTo($model): bool
    {
        return $model instanceof Comment && !$model->trashed();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.comment.delete', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.remove-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::forum.remove-button');
    }
}
