<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelSandbox extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::cp.channel-visibility-label') }}
                </div>
                <div>
                    <input type="hidden" name="sandbox" value="0">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="sandbox"
                            value="1"
                            @checked(old('sandbox', $model->sandbox ?? false))
                        >
                        <span class="stack gap-xxs">
                            <span>{{ __('waterhole::cp.channel-sandbox-label') }}</span>
                            <small class="field__description">{{ __('waterhole::cp.channel-sandbox-description') }}</small>
                        </span>
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['sandbox' => ['nullable', 'boolean']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->sandbox = $request->validated('sandbox');
    }
}
