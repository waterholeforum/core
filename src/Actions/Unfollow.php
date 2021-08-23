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
        return $item instanceof Post && $item->userState->followed_at;
    }

    public function run(Collection $items, Request $request)
    {
        $items->each(fn($item) => $item->userState()->update(['followed_at' => null]));
    }
}
