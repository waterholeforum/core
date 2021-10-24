<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Views\Components\FollowButton;
use Waterhole\Views\TurboStream;

class Unignore extends Action
{
    public function name(): string
    {
        return 'Unignore';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-x-circle';
    }

    public function appliesTo($item): bool
    {
        return method_exists($item, 'unignore');
    }

    public function visible(Collection $items): bool
    {
        return $items->some->isIgnored();
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->unignore();
    }

    public function stream($item): array
    {
        return [
            ...parent::stream($item),
            TurboStream::replace(new FollowButton($item)),
        ];
    }
}
