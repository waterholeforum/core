<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Page;

class PageSlug extends Field
{
    public function __construct(public ?Page $page)
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
                    class="input"
                    value="{{ old('slug', $page->slug ?? null) }}"
                    data-action="slugger#updateSlug"
                    data-slugger-target="slug"
                >
                <p class="field__description">
                    {{ __('waterhole::admin.page-slug-url-label') }}
                    {!! preg_replace('~^https?://~', '', str_replace('*', '<span data-slugger-target="mirror">'.old('slug', $page->slug ?? '').'</span>', route('waterhole.page', ['page' => '*']))) !!}
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
                Rule::unique('pages')->ignore($this->page),
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->page->slug = $request->validated('slug');
    }
}
