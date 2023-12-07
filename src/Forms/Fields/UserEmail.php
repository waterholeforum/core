<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Auth\SsoPayload;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserEmail extends Field
{
    public function __construct(public ?User $model, public ?SsoPayload $payload = null)
    {
        if ($payload) {
            $model->email = $payload->user->email;
        }
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="email"
                :label="__('waterhole::cp.user-email-label')"
            >
                <input
                    type="email"
                    name="email"
                    id="{{ $component->id }}"
                    value="{{ old('email', $model->email ?? null) }}"
                    @disabled($payload)
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        if ($this->payload) {
            return;
        }

        $validator->addRules([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->model),
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        if ($this->payload) {
            return;
        }

        $this->model->email = $request->validated('email');
    }
}
