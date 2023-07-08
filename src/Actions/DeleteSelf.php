<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class DeleteSelf extends Action
{
    public bool $confirm = true;
    public bool $destructive = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof User && !$model->isRootAdmin();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return (bool) $user?->is($model);
    }

    public function shouldRender(Collection $models, string $context = null): bool
    {
        return false;
    }

    public function label(Collection $models): string
    {
        return __('waterhole::user.delete-account-button');
    }

    public function confirm(Collection $models): array
    {
        return [
            __('waterhole::user.delete-account-confirmation-title'),
            __('waterhole::user.delete-account-confirmation-description'),
        ];
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::user.delete-account-button');
    }

    public function run(Collection $models)
    {
        $models[0]->delete();

        auth()->logout();

        session()->flash('success', __('waterhole::user.delete-account-success-message'));

        return redirect('/');
    }
}
