<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\Views\Components\Reactions;
use Waterhole\Views\TurboStream;

class Like extends Action
{
    public bool $hidden = true;

    public function name(): string
    {
        return 'Like';
    }

    public function label(Collection $items): string
    {
        return 'Like';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-thumb-up';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post || $item instanceof Comment;
    }

    public function authorize(?User $user, $item): bool
    {
        return $user && $user->can('like', $item);
    }

    public function run(Collection $items, Request $request)
    {
        $items->each(function ($item) use ($request) {
            $item->likedBy()->toggle([$request->user()->id]);
            $item->refreshLikeMetadata()->save();
        });
    }

    public function stream($item): array
    {
        return [
            TurboStream::replace(new Reactions($item)),
        ];
    }
}
