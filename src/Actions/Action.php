<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\User;

abstract class Action
{
    public bool $destructive = false;
    public bool $confirm = false;
    public bool $bulk = true;

    abstract public function name(): string;

    abstract public function appliesTo($item);

    public function authorize(User $user, $item): bool
    {
        return true;
    }

    public function confirmation(Collection $items): null|string|HtmlString
    {
        return null;
    }

    public function buttonText(Collection $items): ?string
    {
        return 'Confirm';
    }

    public function run(Collection $items, Request $request)
    {
        return null;
    }
}
