<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class DeleteComment extends Action
{
    public bool $confirm = true;

    public bool $destructive = true;

    public function appliesTo($model): bool
    {
        return $model instanceof Comment;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('comment.delete', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.delete-button') . '...';
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function confirm(Collection $models): string
    {
        return __('waterhole::forum.delete-comment-confirm-message');
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.delete-confirm-button');
    }

    public function run(Collection $models)
    {
        $models->each->delete();

        // If the action was initiated from the comment's page, we can't send
        // the user back there. Send them to the comment's post instead.
        if (request('return') === $models[0]->url) {
            return redirect($models[0]->post->url);
        }
    }
}
