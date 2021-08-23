<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

class DeleteChannel extends Action
{
    public bool $destructive = true;
    public bool $confirm = true;
    public bool $bulk = false;

    public function name(): string
    {
        return 'Delete...';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-trash';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Channel;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('delete', $item);
    }

    public function confirmation(Collection $items): string|HtmlString|null
    {
        return new HtmlString(
            view('waterhole::channels.delete', [
                'channel' => $items[0]
            ])
        );
    }

    public function run(Collection $items, Request $request)
    {
        $data = $request->validate([
            'move_posts' => ['boolean'],
            'channel_id' => ['required_if:move_posts,1', Rule::exists(Channel::class, 'id')],
        ]);

        $items->each(function (Channel $channel) use ($data) {
            if ($data['move_posts'] ?? false) {
                $channel->posts()->update(['channel_id' => $data['channel_id']]);
            }

            $channel->delete();
        });

        // TODO: update nav

        return redirect('/');
    }
}
