<?php

namespace Waterhole\Taxonomy\Actions;

use Illuminate\Support\Collection;
use Waterhole\Actions\Link;
use Waterhole\Models\Model;
use Waterhole\Models\User;
use Waterhole\Taxonomy\Tag;

class EditTag extends Link
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Tag;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('tag.edit', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.edit-link');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-pencil';
    }

    public function url(Model $model): string
    {
        return $model->edit_url;
    }

    public function attributes(Collection $models): array
    {
        return ['data-turbo-frame' => 'modal'];
    }
}