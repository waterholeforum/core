<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelColor extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="color"
                :label="__('waterhole::cp.channel-color-label')"
            >
                <x-waterhole::cp.color-picker
                    name="color"
                    id="{{ $component->id }}"
                    value="{{ old('color', $model->color ?? null) }}"
                />
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'color' => [
                'nullable',
                'string',
                'regex:/^[a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8}$/i',
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->color = $request->validated('color');
    }
}
