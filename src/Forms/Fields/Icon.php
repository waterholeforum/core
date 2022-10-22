<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Views\Components\Admin\IconPicker;

class Icon extends Field
{
    public function __construct(public $model = null)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="icon"
                :label="__('waterhole::admin.icon-label')"
            >
                <x-waterhole::admin.icon-picker
                    name="icon"
                    :value="old('icon', $model->icon ?? null)"
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
        $this->model->saveIcon($request->validated('icon'));
    }
}
