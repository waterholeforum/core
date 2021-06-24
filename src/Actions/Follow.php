<?php

namespace Waterhole\Actions;

use Waterhole\Models\Post;

class Follow extends Action
{
    public function name(): string
    {
        return 'Follow';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post && ! $item->tracker()->followed_at;
    }

    public function run($items, $request)
    {
        $items->each(fn($item) => $item->tracker()->update(['followed_at' => now()]));
    }
}
