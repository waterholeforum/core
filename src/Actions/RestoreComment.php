<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class RestoreComment extends Action
{
    public function appliesTo($model): bool
    {
        return $model instanceof Comment && $model->trashed();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user?->can('waterhole.comment.moderate', $model) ||
            ($model->user_id === $user->id && $model->deleted_by === $user->id);
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
        $models->each(function (Comment $comment) {
            $comment->update([
                'deleted_by' => null,
                'deleted_reason' => null,
            ]);
            $comment->restore();
        });
    }
}
