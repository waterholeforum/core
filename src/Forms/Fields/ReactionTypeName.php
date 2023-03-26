<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\ReactionType;

class ReactionTypeName extends Field
{
    public function __construct(public ?ReactionType $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="name"
                :label="__('waterhole::cp.reaction-type-name-label')"
            >
                <input
                    type="text"
                    name="name"
                    id="{{ $component->id }}"
                    value="{{ old('name', $model->name ?? null) }}"
                    autofocus
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['name' => ['required', 'string', 'max:255']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->name = $request->validated('name');
    }
}
