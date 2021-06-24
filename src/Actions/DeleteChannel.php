<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

class DeleteChannel extends Action
{
    public bool $destructive = true;
    public bool $confirm = true;
    public bool $bulk = false;

    public function name(): string
    {
        return 'Delete Channel...';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Channel;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('delete', $item);
    }

    public function confirmation(Collection $items): string|HtmlString|null
    {
        return new HtmlString(
            view('waterhole::channels.delete', [
                'channel' => $items[0]
            ])
        );
    }

    public function run(Collection $items, Request $request): void
    {
        // $items->each->delete();
    }
}
