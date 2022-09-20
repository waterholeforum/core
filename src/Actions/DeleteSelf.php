<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
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

    public function shouldRender(Collection $models): bool
    {
        return false;
    }

    public function label(Collection $models): string
    {
        return 'Delete Your Account';
    }

    public function confirm(Collection $models): HtmlString
    {
        return new HtmlString(
            <<<'html'
                <div class="content">
                    <p class="h4">Are you sure you want to delete your account?</p>
                    <p>Your account data will be removed. Your contributions will be retained but marked as anonymous. This cannot be undone.</p>
                </div>
            html
            ,
        );
    }

    public function confirmButton(Collection $models): string
    {
        return 'Delete Your Account';
    }

    public function run(Collection $models)
    {
        $models[0]->delete();

        auth()->logout();

        session()->flash('success', 'Your account has been deleted.');

        return redirect('/');
    }
}
