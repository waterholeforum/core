<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class DeleteComment extends Action
{
    public bool $destructive = true;

    public function appliesTo($model): bool
    {
        return $model instanceof Comment;
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

    public function confirm(Collection $models): string
    {
        return 'Are you sure you want to delete this comment?';
    }

    public function confirmButton(Collection $models): string
    {
        return 'Delete';
    }

    public function run(Collection $models)
    {
        $models->each->delete();

        session()->flash('success', 'Comment deleted.');

        // If the action was initiated from the comment's page, we can't send
        // the user back there. Send them to the comment's post instead.
        if (request('return') === $models[0]->url) {
            return redirect($models[0]->post->url);
        }
    }
}
