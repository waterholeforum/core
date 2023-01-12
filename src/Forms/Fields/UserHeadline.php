<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserHeadline extends Field
{
    public function __construct(public ?User $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="headline"
                :label="__('waterhole::user.headline-label')"
            >
                <div class="stack gap-xs">
                    <input
                        id="{{ $component->id }}"
                        type="text"
                        name="headline"
                        value="{{ old('headline', $model?->headline) }}"
                        maxlength="30"
                    >
                    <p class="field__description">
                        {{ __('waterhole::user.headline-description') }}
                    </p>
                </div>
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['headline' => ['nullable', 'string', 'max:30']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->headline = $request->validated('headline');
    }
}
