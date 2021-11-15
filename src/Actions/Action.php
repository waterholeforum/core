<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;
use Waterhole\Models\User;

abstract class Action
{
    public ?array $context = null;
    public bool $hidden = false;
    public bool $destructive = false;
    public bool $confirm = false;

    abstract public function name(): string;

    abstract public function appliesTo($item);

    public function authorize(?User $user, $item): bool
    {
        return (bool) $user;
    }

    public function visible(Collection $items, string $context = null): bool
    {
        return ! $this->hidden && (! $context || in_array($context, $this->context ?? []));
    }

    public function attributes(Collection $items): array
    {
        return [];
    }

    public function classes(Collection $items): array
    {
        return [];
    }

    public function icon(Collection $items): ?string
    {
        return null;
    }

    public function label(Collection $items): string|HtmlString
    {
        return $this->name();
    }

    public function confirmation(Collection $items): null|string
    {
        return null;
    }

    public function confirmationBody(Collection $items): null|HtmlString
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

    public function stream($item): array
    {
        $method = $this->destructive ? 'streamRemoved' : 'streamUpdated';

        if (method_exists($item, $method)) {
            return $item->$method();
        }

        return [];
    }

    public function render(Collection $items, ComponentAttributeBag $attributes): HtmlString|null
    {
        $attributes = (new ComponentAttributeBag($attributes->getAttributes()))
            ->merge($this->attributes($items))
            ->class($this->classes($items));

        if ($this->confirm) {
            $attributes = $attributes->merge([
                'formmethod' => 'GET',
                'formaction' => route('waterhole.action.create'),
                'data-turbo-frame' => 'modal',
            ]);
        }

        if ($this->destructive) {
            $attributes = $attributes->class('is-destructive');
        }

        $class = e(static::class);
        $content = $this->renderContent($items);

        return new HtmlString(<<<html
            <button type="submit" name="action_class" value="$class" $attributes>$content</button>
        html);
    }

    protected function renderContent(Collection $items): HtmlString
    {
        $label = e($this->label($items));
        $icon = ($iconName = $this->icon($items)) ? svg($iconName, 'icon')->toHtml() : '';

        return new HtmlString("$icon <span>$label</span>");
    }
}
