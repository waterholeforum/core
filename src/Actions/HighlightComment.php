<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;
use Waterhole\View\TurboStream;

class HighlightComment extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Comment && !$model->trashed();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.comment.moderate', $model);
    }

    public function label(Collection $models): string
    {
        return $models[0]->is_highlighted
            ? __('waterhole::forum.unhighlight-comment-button')
            : __('waterhole::forum.highlight-comment-button');
    }

    public function icon(Collection $models): string
    {
        return $models[0]->is_highlighted ? 'tabler-star-off' : 'tabler-star';
    }

    public function run(Collection $models): void
    {
        $isHighlighted = !$models[0]->is_highlighted;
        $models->each->update(['is_highlighted' => $isHighlighted]);
    }

    public function stream(Model $model): array
    {
        return [TurboStream::refresh()];
    }
}
