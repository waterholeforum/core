<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelSlug extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="slug"
                :label="__('waterhole::cp.channel-slug-label')"
            >
                <input
                    id="{{ $component->id }}"
                    name="slug"
                    type="text"
                    value="{{ old('slug', $model->slug ?? '') }}"
                    data-action="slugger#updateSlug"
                    data-slugger-target="slug"
                >

                <x-slot:description>
                    {{ __('waterhole::cp.channel-slug-url-label') }}
                    {!! preg_replace('~^https?://~', '', str_replace('*', '<span data-slugger-target="mirror">'.old('slug', $model->slug ?? '').'</span>', route('waterhole.channels.show', ['channel' => '*']))) !!}
                </x-slot:description>
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
                Rule::unique('channels')->ignore($this->model),
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->slug = $request->validated('slug');
    }
}
