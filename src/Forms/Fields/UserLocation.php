<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserLocation extends Field
{
    public function __construct(public ?User $user)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="location"
                :label="__('waterhole::user.location-label')"
            >
                <input
                    id="{{ $component->id }}"
                    type="text"
                    name="location"
                    value="{{ old('location', $user?->location) }}"
                    class="input block"
                    maxlength="30"
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['location' => ['nullable', 'string', 'max:30']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->user->location = $request->validated('location');
    }
}
