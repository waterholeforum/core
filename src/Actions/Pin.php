<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class Pin extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post && !$model->is_pinned;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('post.moderate', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.pin-to-top-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-pin';
    }

    public function run(Collection $models): void
    {
        $models->each->update(['is_pinned' => true]);
    }

    public function stream(Model $model): array
    {
        return [];
    }
}
