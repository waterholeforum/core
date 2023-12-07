<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Auth\SsoPayload;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserName extends Field
{
    public function __construct(public ?User $model, public ?SsoPayload $payload = null)
    {
        if ($payload) {
            $model->name = $payload->user->name;
        }
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="name"
                :label="__('waterhole::cp.user-name-label')"
            >
                <input
                    type="text"
                    name="name"
                    id="{{ $component->id }}"
                    value="{{ old('name', $model->name ?? null) }}"
                    autofocus
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'name' => [
                'required',
                'string',
                'max:255',
                // Name cannot contain @, non-space whitespace characters, more
                // than one space in a row, or only numbers.
                'not_regex:/@|[^\S ]|\s{2,}|^\d+$/',
                'not_in:admin',
                Rule::unique(User::class)->ignore($this->model),
            ],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->name = $request->validated('name');
    }
}
