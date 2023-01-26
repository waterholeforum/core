<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Page;

class PageSlug extends Field
{
    public function __construct(public ?Page $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="slug"
                :label="__('waterhole::admin.page-slug-label')"
            >
                <input
                    type="text"
                    name="slug"
                    id="{{ $component->id }}"
                    value="{{ old('slug', $model->slug ?? null) }}"
                    data-action="slugger#updateSlug"
                    data-slugger-target="slug"
                >
                <x-slot:description>
                    {{ __('waterhole::admin.page-slug-url-label') }}
                    {!! preg_replace('~^https?://~', '', str_replace('*', '<span data-slugger-target="mirror">'.old('slug', $model->slug ?? '').'</span>', route('waterhole.page', ['page' => '*']))) !!}
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
                Rule::unique('pages')->ignore($this->model),
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->slug = $request->validated('slug');
    }
}
