<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

class MarkAllAsRead extends Action
{
    public bool $hidden = true;

    public function name(): string
    {
        return 'Mark All as Read';
    }

    public function label(Collection $items): string|HtmlString
    {
        return 'Mark '.($items[0] instanceof Channel ? 'Channel' : 'All').' as Read';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-check-circle';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Channel || $item instanceof User;
    }

    public function authorize(User $user, $item): bool
    {
        return $item instanceof Channel || $item->is($user);
    }

    public function run(Collection $items, Request $request)
    {
        $items->each(function ($item) use ($request) {
            if ($item instanceof Channel) {
                $item->userState->markAsRead()->save();
            } elseif ($item instanceof User) {
                $item->markAllAsRead()->save();
            }
        });
    }
}
