<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserEmail extends Field
{
    public function __construct(public ?User $user)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="email"
                :label="__('waterhole::admin.user-email-label')"
            >
                <input
                    type="email"
                    name="email"
                    id="{{ $component->id }}"
                    class="input"
                    value="{{ old('email', $user->email ?? null) }}"
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user),
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->user->email = $request->validated('email');
    }
}
