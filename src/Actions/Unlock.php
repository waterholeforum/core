<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\Views\Components\CommentsLocked;
use Waterhole\Views\TurboStream;

class Unlock extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post && $model->is_locked;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('post.moderate', $model);
    }

    public function label(Collection $models): string
    {
        return 'Unlock Comments';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-lock-open';
    }

    public function run(Collection $models)
    {
        $models->each->update(['is_locked' => false]);
    }

    public function stream(Model $model): array
    {
        return [
            ...parent::stream($model),
            TurboStream::replace(new CommentsLocked($model)),
        ];
    }
}
