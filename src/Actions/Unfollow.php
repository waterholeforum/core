<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\Post;

class Unfollow extends Action
{
    public function name(): string
    {
        return 'Unfollow';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-x-circle';
    }

    public function appliesTo($item): bool
    {
        return method_exists($item, 'unfollow') && $item->isFollowed();
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->unfollow();

        if ($request->wantsTurboStream() && $items[0] instanceof Post) {
            return response()->turboStreamView('waterhole::posts.stream-updated', ['posts' => $items]);
        }
    }
}
