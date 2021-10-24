<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Views\Components\FollowButton;
use Waterhole\Views\TurboStream;

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
        return method_exists($item, 'unfollow');
    }

    public function visible(Collection $items): bool
    {
        return $items->some->isFollowed();
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->unfollow();
    }

    public function stream($item): array
    {
        return [
            ...parent::stream($item),
            TurboStream::replace(new FollowButton($item)),
        ];
    }
}
