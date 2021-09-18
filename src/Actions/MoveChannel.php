<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class MoveChannel extends Action
{
    public bool $confirm = true;

    public function name(): string
    {
        return 'Move to Channel...';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-arrow-right';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('move', $item);
    }

    public function confirmation(Collection $items): HtmlString
    {
        return new HtmlString(
            view('waterhole::post-move-channel', ['posts' => $items])
        );
    }

    public function buttonText(Collection $items): string
    {
        return 'Move';
    }

    public function run(Collection $items, Request $request)
    {
        $data = $request->validate([
            'channel_id' => ['required', Rule::exists('channels', 'id')],
        ]);

        // TODO: check permission to post in this channel

        $items->each->update($data);
    }
}
