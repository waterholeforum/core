<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Waterhole\Models\User;

class DeleteUser extends Action
{
    public ?array $context = [null, 'admin'];
    public bool $destructive = true;
    public bool $confirm = true;
    public bool $bulk = true;

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
        return $item instanceof User && $item->id !== 1;
    }

    public function authorize(?User $user, $item): bool
    {
        return $user && $user->can('delete', $item) && $user->isNot($item);
    }

    public function confirmation(Collection $items): null|string
    {
        return $items->count() === 1 ? "Delete User: {$items[0]->name}" : "Delete {$items->count()} Users";
    }

    public function confirmationBody(Collection $items): HtmlString
    {
        return new HtmlString(view('waterhole::admin.users.delete'));
    }

    public function run(Collection $items, Request $request)
    {
        DB::transaction(function () use ($items, $request) {
            if ($request->input('delete_content')) {
                $items->each(function (User $user) {
                    $user->posts()->delete();
                    $user->comments()->delete();
                });
            }

            $items->each->delete();
        });

        $request->session()->flash('success', 'User deleted.');

        if ($request->input('return') === $items[0]->url) {
            return redirect('/');
        }
    }
}
