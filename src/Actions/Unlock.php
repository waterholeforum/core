<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\Views\Components\CommentsLocked;
use Waterhole\Views\TurboStream;

class Unlock extends Action
{
    public function name(): string
    {
        return 'Unlock Comments';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-lock-open';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post && $item->is_locked;
    }

    public function authorize(?User $user, $item): bool
    {
        return $user && $user->can('moderate', $item);
    }

    public function run(Collection $items, Request $request)
    {
        $items->each(function ($item) {
            $item->is_locked = false;
            $item->save();
        });
    }

    public function stream($item): array
    {
        return [
            ...parent::stream($item),
            TurboStream::replace(new CommentsLocked($item)),
        ];
    }
}
