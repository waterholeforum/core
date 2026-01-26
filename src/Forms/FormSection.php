<?php

namespace Waterhole\Forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use function Waterhole\build_components;

class FormSection extends Field
{
    public array $components;

    public function __construct(public string $title, public array $items, public bool $open = true)
    {
        $this->components = build_components($items);
    }

    public function shouldRender(): bool
    {
        return !!array_filter($this->components, fn($component) => $component->shouldRender());
    }

    public function render(): string
    {
        return <<<'blade'
            <details class="card" {{ $attributes->merge(['open' => $open]) }}>
                <summary class="card__header h5">
                    {{ $title }}
                </summary>

                <div class="card__body stack dividers">
                    @components($components)
                </div>
            </details>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $this->call('validating', $validator);
    }

    public function saving(FormRequest $request): void
    {
        $this->call('saving', $request);
    }

    public function saved(FormRequest $request): void
    {
        $this->call('saved', $request);
    }

    private function call(string $method, ...$arguments): void
    {
        foreach ($this->items as $item) {
            if ($item instanceof Field) {
                $item->$method(...$arguments);
            }
        }
    }
}
