<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Waterhole\Models\Channel;
use Waterhole\Models\Model;
use Waterhole\Models\User;
use Waterhole\Waterhole;

class DeleteChannel extends Action
{
    public bool $confirm = true;

    public bool $destructive = true;

    public function appliesTo($model): bool
    {
        return $model instanceof Channel;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('channel.delete', $model);
    }

    public function shouldRender(Collection $models): bool
    {
        return Waterhole::isAdminRoute();
    }

    public function label(Collection $models): string
    {
        return 'Delete...';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-trash';
    }

    public function confirm(Collection $models): View
    {
        $channel = $models[0];
        $postCount = $channel->posts()->count();

        return view('waterhole::admin.structure.delete-channel', compact('channel', 'postCount'));
    }

    public function confirmButton(Collection $models): string
    {
        return 'Delete';
    }

    public function run(Collection $models)
    {
        $data = request()->validate([
            'move_posts' => ['boolean'],
            'channel_id' => ['required_if:move_posts,1', Rule::exists(Channel::class, 'id')],
        ]);

        $models->each(function (Channel $channel) use ($data) {
            if ($data['move_posts'] ?? false) {
                $channel->posts()->update(['channel_id' => $data['channel_id']]);
            }

            $channel->delete();
        });
    }
}
