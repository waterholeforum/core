<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;

class ChannelLayoutCards extends Field
{
    public function __construct(public object $model) {}

    public function render()
    {
        return <<<'blade'
            <label class="choice">
                <input type="hidden" name="layout_config_cards[hide_author]" value="1">
                <input type="checkbox" name="layout_config_cards[hide_author]" value="0" @checked(!($model->hide_author ?? false))>
                {{ __('waterhole::cp.channel-layout-show-author-label') }}
            </label>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'layout_config_cards.hide_author' => 'boolean',
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->hide_author = (bool) $request->validated('layout_config_cards.hide_author');
    }
}
