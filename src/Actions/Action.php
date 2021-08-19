<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;
use Waterhole\Models\User;

abstract class Action
{
    public bool $hidden = false;
    public bool $destructive = false;
    public bool $confirm = false;
    public bool $bulk = true;

    abstract public function name(): string;

    abstract public function appliesTo($item);

    public function authorize(User $user, $item): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [];
    }

    public function label(Collection $items): string|HtmlString
    {
        return $this->name();
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

    public function render(Collection $items): HtmlString
    {
        $attributes = new ComponentAttributeBag($this->attributes());

        if ($this->confirm) {
            $attributes = $attributes->merge([
                'formmethod' => 'GET',
                'formaction' => route('waterhole.action.confirm'),
            ]);
        }

        $label = $this->label($items);

        return new HtmlString('<button name="action" value="'.static::class.'" '.$attributes.'>'.$label.'</button>');
    }
}
