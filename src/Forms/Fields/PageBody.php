<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Page;

class PageBody extends Field
{
    public function __construct(public ?Page $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="body"
                :label="__('waterhole::admin.page-body-label')"
            >
                <x-waterhole::text-editor
                    name="body"
                    id="{{ $component->id }}"
                    :value="old('body', $model->body ?? null)"
                    class="input"
                />
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['body' => ['required', 'string']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->body = $request->validated('body');
    }
}
