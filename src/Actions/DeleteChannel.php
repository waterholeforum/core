<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Waterhole\Models\Channel;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class DeleteChannel extends Action
{
    public bool $confirm = true;
    public bool $destructive = true;

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.channel.delete', $model);
    }

    public function shouldRender(Collection $models, string $context = null): bool
    {
        return $context === 'cp';
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.delete-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-trash';
    }

    public function confirm(Collection $models): View
    {
        $channel = $models[0];
        $postCount = $channel->posts()->count();

        return view('waterhole::cp.structure.delete-channel', compact('channel', 'postCount'));
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.delete-confirm-button');
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
