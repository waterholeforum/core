<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelInstructions extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="instructions"
                :label="__('waterhole::admin.channel-instructions-label')"
            >
                <x-waterhole::text-editor
                    name="instructions"
                    :id="$component->id"
                    :value="old('instructions', $model->instructions ?? '')"
                    class="input"
                />
                <p class="field__description">
                    {{ __('waterhole::admin.channel-instructions-description') }}
                </p>
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['instructions' => ['nullable', 'string']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->instructions = $request->validated('instructions');
    }
}
