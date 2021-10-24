<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class React extends Action
{
    public bool $hidden = true;

    public function name(): string
    {
        return 'React';
    }

    public function label(Collection $items): string|HtmlString
    {
        return 'Like';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-thumb-up';
    }

    // public function classes(Collection $items): array
    // {
    //     return [
    //         'is-liked' => $items[0]->likedBy->contains(Auth::user()->id),
    //     ];
    // }

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

        if ($request->wantsTurboStream()) {
            return response()->turboStreamView('waterhole::comments.stream-reactions', ['comments' => $items]);
        }
    }
}
