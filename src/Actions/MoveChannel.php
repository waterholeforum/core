<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class MoveChannel extends Action
{
    public bool $confirm = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('post.move', $model);
    }

    public function label(Collection $models): string
    {
        return 'Move to Channel...';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-arrow-right';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::posts.move-channel', ['posts' => $models]);
    }

    public function confirmButton(Collection $models): string
    {
        return 'Move';
    }

    public function run(Collection $models)
    {
        $data = request()->validate([
            'channel_id' => ['required', Rule::exists('channels', 'id')],
        ]);

        // TODO: check permission to post in this channel

        $models->each->update($data);
    }
}
