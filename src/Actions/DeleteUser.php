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
        return $model instanceof User && ! $model->isRootAdmin();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user
            && $user->can('user.delete', $model)
            && $user->isNot($model)
            && ! $model->isRootAdmin();
    }

    public function label(Collection $models): string
    {
        return 'Delete...';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-trash';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::admin.users.delete', [
            'users' => $models,
        ]);
    }

    public function confirmButton(Collection $models): string
    {
        return 'Delete';
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

        session()->flash('success', 'User deleted.');

        // If the action was initiated from the user's page, we can't send the
        // user back there. Instead, send them to the forum index.
        if (request('return') === $models[0]->url) {
            return redirect('/');
        }
    }
}
