<?php

namespace Waterhole\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Waterhole\Actions\Concerns\ResolvesFlags;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class RemoveComment extends Action
{
    use ResolvesFlags;

    public function appliesTo($model): bool
    {
        return $model instanceof Comment && !$model->trashed();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.comment.delete', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.remove-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function shouldConfirm(Collection $models): bool
    {
        return request()->user()->id !== $models[0]->user_id;
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::moderation.removal-reason', [
            'reasons' => config('waterhole.forum.report_reasons'),
        ]);
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::forum.remove-button');
    }

    public function run(Collection $models)
    {
        $actor = request()->user();

        $data = request()->validate([
            'deleted_reason' => [
                'string',
                'nullable',
                Rule::in(config('waterhole.forum.report_reasons')),
            ],
        ]);

        $models->each(function (Comment $comment) use ($actor, $data) {
            $comment->update([
                'deleted_by' => $actor->id,
                'deleted_reason' =>
                    $actor->id === $comment->user_id ? null : $data['deleted_reason'] ?? null,
            ]);
            $comment->delete();
        });

        return $this->resolveFlags($models);
    }
}
