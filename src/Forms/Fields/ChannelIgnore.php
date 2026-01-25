<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelIgnore extends Field
{
    public function __construct(public ?Channel $model) {}

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::cp.channel-visibility-label') }}
                </div>
                <div>
                    <input type="hidden" name="ignore" value="0">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="ignore"
                            value="1"
                            @checked(old('ignore', $model->ignore ?? false))
                        >
                        <span class="stack gap-xxs">
                            <span>{{ __('waterhole::cp.channel-ignore-label') }}</span>
                            <small class="field__description">{{ __('waterhole::cp.channel-ignore-description') }}</small>
                        </span>
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['ignore' => ['nullable', 'boolean']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->ignore = $request->validated('ignore');
    }
}
