<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelDescription extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="description"
                :label="__('waterhole::admin.channel-description-label')"
                :description="__('waterhole::admin.channel-description-description')"
            >
                <x-waterhole::text-editor
                    name="description"
                    :id="$component->id"
                    :value="old('description', $model->description ?? '')"
                />
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
