<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Waterhole\Models\Channel;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class MoveToChannel extends Action
{
    public bool $confirm = true;

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.post.move', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.move-to-channel-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-arrow-right';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::posts.move-to-channel', ['posts' => $models]);
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.save-changes-button');
    }

    public function run(Collection $models)
    {
        $data = request()->validate([
            'channel_id' => ['required', Rule::exists(Channel::class, 'id')],
        ]);

        Gate::authorize('waterhole.channel.post', Channel::findOrFail($data['channel_id']));

        $models->each->update($data);
    }
}
