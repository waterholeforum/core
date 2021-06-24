<?php

namespace Waterhole\Actions;

use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class Unpin extends Action
{
    public function name(): string
    {
        return 'Unpin';
    }

    public function appliesTo($item): bool
    {
        return ($item instanceof Post || $item instanceof Comment) && $item->is_pinned;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('pin', $item);
    }

    public function run($items, $request): void
    {
        $items->each->update(['is_pinned' => false]);
    }
}
