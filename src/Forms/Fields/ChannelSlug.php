<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelSlug extends Field
{
    public function __construct(public ?Channel $channel)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="slug"
                :label="__('waterhole::admin.channel-slug-label')"
            >
                <input
                    id="{{ $component->id }}"
                    name="slug"
                    type="text"
                    value="{{ old('slug', $channel->slug ?? '') }}"
                    class="input"
                    data-action="slugger#updateSlug"
                    data-slugger-target="slug"
                >
                <p class="field__description">
                    {{ __('waterhole::admin.channel-slug-url-label') }}
                    {!! preg_replace('~^https?://~', '', str_replace('*', '<span data-slugger-target="mirror">'.old('slug', $channel->slug ?? '').'</span>', route('waterhole.channels.show', ['channel' => '*']))) !!}
                </p>
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('channels')->ignore($this->channel),
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->channel->slug = $request->validated('slug');
    }
}
