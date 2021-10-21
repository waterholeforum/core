<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\Post;

class Ignore extends Action
{
    public function name(): string
    {
        return 'Ignore';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-volume-off';
    }

    public function appliesTo($item): bool
    {
        return method_exists($item, 'ignore') && ! $item->isFollowed();
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->ignore();

        if ($request->wantsTurboStream() && $items[0] instanceof Post) {
            return response()->turboStreamView('waterhole::posts.stream-updated', ['posts' => $items]);
        }
    }
}
