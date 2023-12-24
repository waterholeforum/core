<?php

namespace Waterhole\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class HideComment extends Action
{
    public const REASONS = ['off-topic', 'inappropriate', 'spam'];

    public bool $confirm = true;

    public function appliesTo($model): bool
    {
        return $model instanceof Comment && !$model->isHidden();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.comment.moderate', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.hide-comment-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-eye-off';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::comments.hide', [
            'comments' => $models,
            'reasons' => static::REASONS,
        ]);
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::forum.hide-comment-button');
    }

    public function run(Collection $models): void
    {
        $data = request()->validate([
            'hidden_reason' => ['string', 'nullable', Rule::in(static::REASONS)],
        ]);

        $models->each->update([
            'hidden_at' => now(),
            'hidden_by' => Auth::id(),
            'hidden_reason' => $data['hidden_reason'],
        ]);
    }
}
