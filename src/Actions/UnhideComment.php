<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class UnhideComment extends Action
{
    public function appliesTo($model): bool
    {
        return $model instanceof Comment && $model->isHidden();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.comment.moderate', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.unhide-comment-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-eye';
    }

    public function run(Collection $models)
    {
        $models->each->update([
            'hidden_at' => null,
            'hidden_by' => null,
            'hidden_reason' => null,
        ]);
    }
}
