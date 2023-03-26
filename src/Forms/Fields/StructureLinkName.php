<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\StructureLink;

class StructureLinkName extends Field
{
    public function __construct(public ?StructureLink $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="name"
                :label="__('waterhole::cp.link-name-label')"
            >
                <input
                    id="{{ $component->id }}"
                    name="name"
                    type="text"
                    value="{{ old('name', $model->name ?? '') }}"
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
