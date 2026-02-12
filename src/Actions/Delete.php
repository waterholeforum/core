<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class Delete extends Action
{
    public bool $confirm = true;
    public bool $destructive = true;

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can($this->ability($model), $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.delete-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function confirm(Collection $models): string
    {
        $key = "waterhole::cp.delete-{$this->resource($models[0])}-confirm-message";
        $message = __($key);

        return $message === $key ? __('waterhole::system.delete-confirm-button') : $message;
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.delete-confirm-button');
    }

    public function run(Collection $models)
    {
        $models->each->delete();
    }

    protected function resource(Model $model): string
    {
        return (string) str(class_basename($model))->kebab();
    }

    protected function ability(Model $model): string
    {
        return "waterhole.{$this->resource($model)}.delete";
    }
}
