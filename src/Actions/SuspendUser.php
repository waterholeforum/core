<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class SuspendUser extends Action
{
    public bool $confirm = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof User && !$model->isRootAdmin();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.user.suspend', $model) && $user->isNot($model);
    }

    public function label(Collection $models): string
    {
        return $models[0]->suspended_until?->isFuture()
            ? __('waterhole::user.edit-suspension-button')
            : __('waterhole::user.suspend-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-ban';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::cp.users.suspend', ['users' => $models]);
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::user.suspend-button');
    }

    public function run(Collection $models)
    {
        $data = request()->validate([
            'status' => ['required', 'in:none,indefinite,custom'],
            'suspended_until' => ['nullable', 'date'],
        ]);

        $models->each->update([
            'suspended_until' => match ($data['status']) {
                'none' => null,
                'indefinite' => '2038-01-01',
                'custom' => $data['suspended_until'],
            },
        ]);
    }
}
