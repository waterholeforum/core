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
        return $model instanceof Post;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.post.moderate', $model);
    }

    public function label(Collection $models): string
    {
        return $models[0]->is_pinned
            ? __('waterhole::forum.unpin-button')
            : __('waterhole::forum.pin-to-top-button');
    }

    public function icon(Collection $models): string
    {
        return $models[0]->is_pinned ? 'tabler-pinned-off' : 'tabler-pin';
    }

    public function run(Collection $models): void
    {
        $isPinned = !$models[0]->is_pinned;
        $models->each->update(['is_pinned' => $isPinned]);
    }

    public function stream(Model $model): array
    {
        return [];
    }
}
