<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class Unpin extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post && $model->is_pinned;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('post.moderate', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.unpin-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-pinned-off';
    }

    public function run(Collection $models): void
    {
        $models->each->update(['is_pinned' => false]);
    }

    public function stream(Model $model): array
    {
        return [];
    }
}
