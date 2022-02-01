<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;

class MarkAsRead extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post && $model->isUnread();
    }

    public function label(Collection $models): string
    {
        return 'Mark as Read';
    }

    public function icon(Collection $models): ?string
    {
        return 'heroicon-s-check';
    }

    public function run(Collection $models)
    {
        $models->each(function ($post) {
            $post->userState->read()->save();
            $post->refresh();
        });
    }
}
