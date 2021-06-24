<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserBio extends Field
{
    public function __construct(public ?User $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="bio"
                :label="__('waterhole::user.bio-label')"
                :description="__('waterhole::user.bio-description')"
            >
                <textarea
                    id="{{ $component->id }}"
                    type="text"
                    name="bio"
                    maxlength="255"
                >{{ old('bio', $model?->bio) }}</textarea>
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['bio' => ['nullable', 'string', 'max:255']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->bio = $request->validated('bio');
    }
}
