<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Waterhole\Auth\RegistrationPayload;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserPassword extends Field
{
    public function __construct(public User $model, public ?RegistrationPayload $payload = null)
    {
    }

    public function shouldRender(): bool
    {
        return !$this->payload;
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="password"
                :label="__('waterhole::cp.user-password-label')"
            >
                @if ($model->exists)
                    <div data-controller="reveal" class="stack gap-sm">
                        <label class="choice">
                            <input type="checkbox" data-reveal-target="if">
                            {{ __('waterhole::cp.user-set-password-label') }}
                        </label>
                @endif
                        <input
                            data-reveal-target="then"
                            type="password"
                            name="password"
                            id="{{ $component->id }}"

                        >
                @if ($model->exists)
                    </div>
                @endif
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        if ($this->payload) {
            return;
        }

        $validator->addRules([
            'password' => [$this->model->exists ? 'nullable' : 'required', Password::defaults()],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        if ($this->payload) {
            return;
        }

        if ($password = $request->validated('password')) {
            $this->model->password = Hash::make($password);
        }
    }
}
