<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\Structure;
use Waterhole\Models\User;

class DeleteStructure extends Action
{
    public ?array $context = ['admin'];
    public bool $destructive = true;
    public bool $confirm = true;
    public bool $bulk = false;

    public function name(): string
    {
        return 'Delete...';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-trash';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Structure;
    }

    public function authorize(?User $user, $item): bool
    {
        return $user && $user->can('delete', $item);
    }

    public function run(Collection $items, Request $request)
    {


        return redirect('/');
    }
}
