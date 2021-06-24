<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelDefaultLayout extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::admin.channel-default-layout-label') }}
                </div>
                <div class="stack gap-xs">
                    @foreach (['list' => 'tabler-list', 'cards' => 'tabler-layout-list'] as $key => $icon)
                        <label class="choice">
                            <input
                                type="radio"
                                name="default_layout"
                                id="default_layout_{{ $key }}"
                                value="{{ $key }}"
                                @checked(old('default_layout', $model->default_layout ?? config('waterhole.forum.default_post_layout')) === $key)
                            >
                            <span class="with-icon">
                                <x-waterhole::icon :icon="$icon"/>
                                {{ __("waterhole::system.layout-$key") }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['default_layout' => ['in:list,cards']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->default_layout = $request->validated('default_layout');
    }
}
