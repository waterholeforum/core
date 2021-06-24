<?php

namespace Waterhole\Actions;

use Waterhole\Models\Post;

class Unfollow extends Action
{
    public function name(): string
    {
        return 'Unfollow';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post && $item->tracker()->followed_at;
    }

    public function run($items)
    {
        $items->each(fn($item) => $item->tracker()->update(['followed_at' => null]));
    }
}
