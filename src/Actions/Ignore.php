<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Views\Components\FollowButton;
use Waterhole\Views\TurboStream;

class Ignore extends Action
{
    public function name(): string
    {
        return 'Ignore';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-volume-off';
    }

    public function appliesTo($item): bool
    {
        return method_exists($item, 'ignore');
    }

    public function visible(Collection $items, string $context = null): bool
    {
        return parent::visible($items, $context) && $items->some(fn($item) => ! $item->userState->notifications);
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->ignore();
    }

    public function stream($item): array
    {
        return [
            ...parent::stream($item),
            TurboStream::replace(new FollowButton($item)),
        ];
    }
}
