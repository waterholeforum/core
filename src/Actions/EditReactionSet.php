<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\User;

class EditReactionSet extends Link
{
    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.reaction-set.edit', $model);
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
}
