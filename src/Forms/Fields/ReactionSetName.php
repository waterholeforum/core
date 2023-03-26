<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\ReactionSet;

class ReactionSetName extends Field
{
    public function __construct(public ?ReactionSet $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="name"
                :label="__('waterhole::cp.reaction-set-name-label')"
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
