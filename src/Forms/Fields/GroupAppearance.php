<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Group;
use Waterhole\Views\Components\Admin\IconPicker;

class GroupAppearance extends Field
{
    public function __construct(public ?Group $group)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field" data-controller="reveal">
                <div class="field__label">{{ __('waterhole::admin.group-appearance-label') }}</div>

                <div class="stack gap-lg">
                    <div>
                        <input type="hidden" name="is_public" value="0">
                        <label class="choice">
                            <input
                                data-reveal-target="if"
                                type="checkbox"
                                name="is_public"
                                value="1"
                                @checked(old('is_public', $group->is_public ?? null))
                            >
                            {{ __('waterhole::admin.group-show-as-badge-label') }}
                        </label>
                    </div>

                    <x-waterhole::field
                        name="color"
                        :label="__('waterhole::admin.group-color-label')"
                        data-reveal-target="then"
                    >
                        <x-waterhole::admin.color-picker
                            name="color"
                            id="{{ $component->id }}"
                            value="{{ old('color', $group->color ?? null) }}"
                        />
                    </x-waterhole::field>

                    <x-waterhole::field
                        name="icon"
                        :label="__('waterhole::admin.group-icon-label')"
                        data-reveal-target="then"
                    >
                        <x-waterhole::admin.icon-picker
                            name="icon"
                            :value="old('icon', $group->icon ?? null)"
                        />
                    </x-waterhole::field>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'is_public' => ['boolean'],
            'color' => ['nullable', 'string', 'regex:/^[a-f0-9]{3}|[a-f0-9]{6}$/i'],
        ]);

        $validator->addRules(IconPicker::validationRules());
    }

    public function saving(FormRequest $request): void
    {
        $this->group->name = $request->validated('name');
    }

    public function saved(FormRequest $request): void
    {
        $this->group->saveIcon($request->validated('icon'));
    }
}
