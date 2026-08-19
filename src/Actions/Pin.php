<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Waterhole\Models\Enums\PinnedScope;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\View\TurboStream;

class Pin extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post;
    }

    public function authorize(?User $user, Model $model): bool
    {
        if (!$user) {
            return false;
        }

        return $model->pinned_scope === PinnedScope::Global
            ? $user->isAdmin()
            : $user->can('waterhole.post.moderate', $model);
    }

    public function shouldConfirm(Collection $models): bool
    {
        return !$models[0]->pinned_scope && request()->user()->isAdmin();
    }

    public function label(Collection $models): string
    {
        return $models[0]->pinned_scope
            ? __('waterhole::forum.unpin-button')
            : __('waterhole::forum.pin-to-top-button');
    }

    public function icon(Collection $models): string
    {
        return $models[0]->pinned_scope ? 'tabler-pinned-off' : 'tabler-pin';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::posts.pin');
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::forum.pin-button');
    }

    public function run(Collection $models): void
    {
        if ($models[0]->pinned_scope) {
            $models->each->update(['pinned_scope' => null]);

            return;
        }

        if (!request()->user()->isAdmin()) {
            $models->each->update(['pinned_scope' => PinnedScope::Channel]);

            return;
        }

        $data = request()->validate([
            'pinned_scope' => ['required', Rule::enum(PinnedScope::class)],
        ]);
        $models->each->update($data);
    }

    public function stream(Model $model): array
    {
        return [TurboStream::refresh()];
    }
}
