<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\View\Components\CommentsLocked;
use Waterhole\View\TurboStream;

class Lock extends Action
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
        return $models[0]->is_locked
            ? __('waterhole::forum.unlock-comments-button')
            : __('waterhole::forum.lock-comments-button');
    }

    public function icon(Collection $models): string
    {
        return $models[0]->is_locked ? 'tabler-lock-open' : 'tabler-lock';
    }

    public function run(Collection $models)
    {
        $isLocked = !$models[0]->is_locked;
        $models->each->update(['is_locked' => $isLocked]);
    }

    public function stream(Model $model): array
    {
        return [...parent::stream($model), TurboStream::replace(new CommentsLocked($model))];
    }
}
