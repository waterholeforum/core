<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelDescription extends Field
{
    public function __construct(public ?Channel $channel)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="description"
                :label="__('waterhole::admin.channel-description-label')"
            >
                <textarea
                    id="{{ $component->id }}"
                    name="description"
                    class="input"
                >{{ old('description', $channel->description ?? '') }}</textarea>
                <p class="field__description">
                    {{ __('waterhole::admin.channel-description-description') }}
                </p>
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['description' => ['nullable', 'string']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->channel->description = $request->validated('description');
    }
}
