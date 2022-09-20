<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class EditComment extends Link
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Comment;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('comment.edit', $model);
    }

    public function label(Collection $models): string
    {
        return 'Edit';
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
