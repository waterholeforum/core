<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\User;

class Delete extends Action
{
    public bool $destructive = true;
    public bool $confirm = true;

    public function name(): string
    {
        return 'Delete';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Deletable;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('delete', $item);
    }

    public function confirmation(Collection $items): string
    {
        return 'Are you sure you want to delete this?';
    }

    public function run(Collection $items, Request $request): void
    {
        $items->each->delete();
    }
}
