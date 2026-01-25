<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserShowOnline extends Field
{
    public function __construct(public ?User $model) {}

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">{{ __('waterhole::user.privacy-title') }}</div>
                <div>
                    <input type="hidden" name="show_online" value="0">
                    <label for="show_online" class="choice">
                        <input
                            id="show_online"
                            type="checkbox"
                            name="show_online"
                            value="1"
                            @checked($model?->show_online)
                        >
                        {{ __('waterhole::user.show-online-label') }}
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['show_online' => ['boolean']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->show_online = $request->validated('show_online');
    }
}
