<?php

namespace Waterhole\Forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FormSection extends Field
{
    public function __construct(
        public string $title,
        public array $items,
        public bool $open = true,
    ) {}

    public function render(): string
    {
        return <<<'blade'
            <details class="card" {{ $attributes->merge(['open' => $open]) }}>
                <summary class="card__header h5">
                    {{ $title }}
                </summary>

                <div class="card__body stack dividers">
                    @components($items)
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
