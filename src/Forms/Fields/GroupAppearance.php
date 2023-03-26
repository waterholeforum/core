<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Group;
use Waterhole\View\Components\Cp\IconPicker;

class GroupAppearance extends Field
{
    public function __construct(public ?Group $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field" data-controller="reveal">
                <div class="field__label">{{ __('waterhole::cp.group-appearance-label') }}</div>

                <div class="stack gap-lg">
                    <div>
                        <input type="hidden" name="is_public" value="0">
                        <label class="choice">
                            <input
                                data-reveal-target="if"
                                type="checkbox"
                                name="is_public"
                                value="1"
                                @checked(old('is_public', $model->is_public ?? null))
                            >
                            {{ __('waterhole::cp.group-show-as-badge-label') }}
                        </label>
                    </div>

                    <div class="card card__body stack gap-lg" data-reveal-target="then">
                        <x-waterhole::field
                            name="color"
                            :label="__('waterhole::cp.group-color-label')"
                        >
                            <x-waterhole::cp.color-picker
                                name="color"
                                id="{{ $component->id }}"
                                value="{{ old('color', $model->color ?? null) }}"
                            />
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="icon"
                            :label="__('waterhole::cp.group-icon-label')"
                        >
                            <x-waterhole::cp.icon-picker
                                name="icon"
                                :value="old('icon', $model->icon ?? null)"
                            />
                        </x-waterhole::field>
                    </div>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'is_public' => ['boolean'],
            'color' => [
                'nullable',
                'string',
                'regex:/^[a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8}$/i',
            ],
        ]);

        $validator->addRules(IconPicker::validationRules());
    }

    public function saving(FormRequest $request): void
    {
        $this->model->is_public = $request->validated('is_public');
        $this->model->color = $request->validated('color');
    }

    public function saved(FormRequest $request): void
    {
        $this->model->saveIcon($request->validated('icon'));
    }
}
