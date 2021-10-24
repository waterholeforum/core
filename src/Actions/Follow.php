<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Views\Components\FollowButton;
use Waterhole\Views\TurboStream;

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
        return method_exists($item, 'follow');
    }

    public function visible(Collection $items): bool
    {
        return $items->some(fn($item) => ! $item->userState->notifications);
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->follow();
    }

    public function stream($item): array
    {
        return [
            ...parent::stream($item),
            TurboStream::replace(new FollowButton($item)),
        ];
    }
}
