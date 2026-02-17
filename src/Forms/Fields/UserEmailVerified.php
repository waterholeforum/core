<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserEmailVerified extends Field
{
    public function __construct(public ?User $model) {}

    public function render(): string
    {
        return <<<'blade'
            <div class="stack gap-sm">
                <x-waterhole::field
                    name="email"
                    :label="__('waterhole::cp.user-email-label')"
                >
                    <div class="stack gap-sm">
                        <input
                            type="email"
                            name="email"
                            id="{{ $component->id }}"
                            value="{{ old('email', $model->email ?? null) }}"
                        >

                        <label for="email_verified" class="choice">
                            <input type="hidden" name="email_verified" value="0">
                            <input
                                id="email_verified"
                                type="checkbox"
                                name="email_verified"
                                value="1"
                                @checked(old('email_verified', $model?->exists ? $model->hasVerifiedEmail() : true))
                            >
                            <span>{{ __('waterhole::cp.user-email-verified-label') }}</span>
                        </label>
                    </div>
                </x-waterhole::field>
            </div>
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
                Rule::unique(User::class)->ignore($this->model),
            ],
            'email_verified' => ['nullable', 'boolean'],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->email = $request->validated('email');

        if ($request->has('email_verified')) {
            $this->model->email_verified_at = $request->validated('email_verified')
                ? $this->model->email_verified_at ?? now()
                : null;
        }
    }
}
