<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class DeleteUser extends Action
{
    public bool $confirm = true;
    public bool $destructive = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof User && !$model->isRootAdmin();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('user.delete', $model) && $user->isNot($model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.delete-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::cp.users.delete', [
            'users' => $models,
        ]);
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.delete-confirm-button');
    }

    public function run(Collection $models)
    {
        DB::transaction(function () use ($models) {
            if (request('delete_content')) {
                $models->each(function (User $user) {
                    $user->posts()->delete();
                    $user->comments()->delete();
                });
            }

            $models->each->delete();
        });

        session()->flash('success', __('waterhole::cp.delete-user-success-message'));

        // If the action was initiated from the user's page, we can't send the
        // user back there. Instead, send them to the forum index.
        if (str_starts_with(request('return'), $models[0]->url)) {
            return redirect('/');
        }
    }
}
