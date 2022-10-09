<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Views\Components\Admin\IconPicker;

class ChannelIcon extends Field
{
    public function __construct(public ?Channel $channel)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="icon"
                :label="__('waterhole::admin.channel-icon-label')"
            >
                <x-waterhole::admin.icon-picker
                    name="icon"
                    :value="old('icon', $channel->icon ?? null)"
                />
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(IconPicker::validationRules());
    }

    public function saved(FormRequest $request): void
    {
        $this->channel->saveIcon($request->validated('icon'));
    }
}
