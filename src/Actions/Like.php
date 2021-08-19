<?php

namespace Waterhole\Actions;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class Like extends Action
{
    public bool $hidden = false;
    public bool $bulk = false;

    public function name(): string
    {
        return 'Like';
    }

    public function label($items): string|HtmlString
    {
        return $items[0]->score ?: 'Like';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post || $item instanceof Comment;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('like', $item);
    }

    public function run($items, $request)
    {
        $items->each(function ($item) use ($request) {
            $item->likedBy()->toggle([$request->user()->id]);
            $item->refreshLikeMetadata()->save();
        });
    }
}
