<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\Post;

class MarkAsUnread extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post && $model->isRead();
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.mark-as-unread-button');
    }

    public function icon(Collection $models): ?string
    {
        return 'tabler-point-filled';
    }

    public function run(Collection $models)
    {
        $models->each(function (Post $post) {
            $post->userState->unread()->save();
            $post->refresh();
        });
    }
}
