<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelName extends Field
{
    public function __construct(public ?Channel $channel)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="name"
                :label="__('waterhole::admin.channel-name-label')"
            >
                <input
                    id="{{ $component->id }}"
                    name="name"
                    type="text"
                    value="{{ old('name', $channel->name ?? '') }}"
                    class="input"
                    data-action="slugger#updateName"
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
        $this->channel->name = $request->validated('name');
    }
}
