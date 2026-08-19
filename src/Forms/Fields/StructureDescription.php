<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\StructureLink;

class StructureDescription extends Field
{
    public function __construct(
        public Channel|Page|StructureLink|null $model,
    ) {}

    public function render(): string
    {
        return <<<'blade'
                <x-waterhole::field
                    name="description"
                    :label="__('waterhole::cp.structure-description-label')"
                    :description="__('waterhole::cp.structure-description-description')"
                >
                    <textarea
                        id="{{ $component->id }}"
                        name="description"
                    >{{ old('description', $model->description ?? '') }}</textarea>
                </x-waterhole::field>
            blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['description' => ['nullable', 'string']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->description = $request->validated('description');
    }
}
