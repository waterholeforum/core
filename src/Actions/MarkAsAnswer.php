<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class MarkAsAnswer extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Comment;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return (bool) $user?->can('post.edit', $model->post);
    }

    public function shouldRender(Collection $models): bool
    {
        return false;
    }

    public function label(Collection $models): string
    {
        return $models[0]->isAnswer()
            ? __('waterhole::forum.unmark-as-answer-button')
            : __('waterhole::forum.mark-as-answer-button');
    }

    public function icon(Collection $models): ?string
    {
        return $models[0]->isAnswer() ? 'tabler-x' : 'tabler-check';
    }

    public function run(Collection $models)
    {
        [$comment] = $models;

        $relationship = $comment->post->answer();

        if ($comment->isAnswer()) {
            $relationship->dissociate()->save();
        } else {
            $relationship->associate($comment)->save();
        }

        return redirect($models[0]->post->url);
    }

    public function stream(Model $model): array
    {
        return [];
    }
}
