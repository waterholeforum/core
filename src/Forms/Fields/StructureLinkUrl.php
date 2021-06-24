<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\StructureLink;

class StructureLinkUrl extends Field
{
    public function __construct(public ?StructureLink $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="url"
                :label="__('waterhole::admin.link-url-label')"
            >
                <input
                    type="text"
                    name="url"
                    id="{{ $component->id }}"
                    value="{{ old('url', $model->href ?? null) }}"
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['url' => ['required', 'string', 'max:255']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->href = $request->validated('url');
    }
}
