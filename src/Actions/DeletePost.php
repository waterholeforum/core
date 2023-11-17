<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class DeletePost extends Action
{
    public bool $confirm = true;

    public bool $destructive = true;

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
        return __('waterhole::forum.delete-forever-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash-x';
    }

    public function confirm(Collection $models): string
    {
        return __('waterhole::forum.delete-post-confirm-message');
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.delete-confirm-button');
    }

    public function run(Collection $models)
    {
        $models->each->forceDelete();

        session()->flash('success', __('waterhole::forum.delete-post-success-message'));

        // If the action was initiated from the post's page, we can't send the
        // user back there. Instead, send them to the forum index.
        if (request('return') === $models[0]->url) {
            return redirect('/');
        }
    }
}
