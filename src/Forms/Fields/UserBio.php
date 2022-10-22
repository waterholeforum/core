<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserBio extends Field
{
    public function __construct(public ?User $user)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field name="bio" :label="__('waterhole::user.bio-label')">
                <div class="stack gap-xs">
                    <textarea
                        id="{{ $component->id }}"
                        type="text"
                        name="bio"
                        class="input block"
                        maxlength="255"
                    >{{ old('bio', $user?->bio) }}</textarea>
                    <p class="field__description">
                        {{ __('waterhole::user.bio-description') }}
                    </p>
                </div>
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['bio' => ['nullable', 'string', 'max:255']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->user->bio = $request->validated('bio');
    }
}