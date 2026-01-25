<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;

class ChannelLayoutList extends Field
{
    public function __construct(public object $model) {}

    public function render()
    {
        return <<<'blade'
            <div class="stack dividers">
                <div class="stack gap-sm">
                    <label class="choice">
                        <input type="hidden" name="layout_config_list[show_excerpt]" value="0">
                        <input type="checkbox" name="layout_config_list[show_excerpt]" value="1" @checked($model->show_excerpt ?? false)>
                        {{ __('waterhole::cp.channel-layout-show-excerpt-label') }}
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'layout_config_list.show_excerpt' => 'boolean',
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->show_excerpt = (bool) $request->validated('layout_config_list.show_excerpt');
    }
}
