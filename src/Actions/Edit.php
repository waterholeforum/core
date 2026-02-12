<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class Edit extends Link
{
    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can($this->ability($model), $model);
    }

    public function label(Collection $models): string
    {
        $key = "waterhole::forum.edit-{$this->resource($models[0])}-link";
        $label = __($key);

        return $label === $key ? __('waterhole::system.edit-link') : $label;
    }

    public function icon(Collection $models): string
    {
        return 'tabler-pencil';
    }

    public function url(Model $model): string
    {
        return $model->edit_url;
    }

    protected function resource(Model $model): string
    {
        return (string) str(class_basename($model))->kebab();
    }

    protected function ability(Model $model): string
    {
        return "waterhole.{$this->resource($model)}.edit";
    }
}
