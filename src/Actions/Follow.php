<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

class Follow extends Action
{
    public function name(): string
    {
        return 'Follow';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-bell';
    }

    public function appliesTo($item): bool
    {
        return ($item instanceof Post && ! $item->userState->followed_at)
            || $item instanceof Channel; // temp
    }

    public function run(Collection $items, Request $request)
    {
        $items->each(fn($item) => $item->userState()->update(['followed_at' => now()]));
    }
}
