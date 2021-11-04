<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\User;

class DeleteComment extends Action
{
    public bool $destructive = true;
    public bool $confirm = true;

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
        return $item instanceof Comment;
    }

    public function authorize(?User $user, $item): bool
    {
        return $user && $user->can('delete', $item);
    }

    public function confirmation(Collection $items): string
    {
        return 'Are you sure you want to delete this comment?';
    }

    public function confirmationBody(Collection $items): HtmlString
    {
        return new HtmlString('<p>This action cannot be undone.</p>');
    }

    public function buttonText(Collection $items): ?string
    {
        return 'Delete';
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->delete();

        $request->session()->flash('success', 'Comment deleted.');

        if ($request->get('return') === $items[0]->url) {
            return redirect('/');
        }
    }
}
