<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelAnswers extends Field
{
    public function __construct(public ?Channel $model) {}

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label with-icon">
                    @icon('tabler-check', ['class' => 'text-md'])
                    {{ __('waterhole::cp.channel-answers-label') }}
                </div>
                <div>
                    <input type="hidden" name="answerable" value="0">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="answerable"
                            value="1"
                            @checked(old('answerable', $model->answerable))
                        >
                        <span class="stack gap-xxs">
                            <span>{{ __('waterhole::cp.channel-enable-answers-label') }}</span>
                            <small class="field__description">{{ __('waterhole::cp.channel-enable-answers-description') }}</small>
                        </span>
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['answerable' => ['nullable', 'boolean']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->answerable = $request->validated('answerable');
    }
}
