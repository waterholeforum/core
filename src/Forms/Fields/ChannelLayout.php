<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Extend\PostLayouts;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use function Waterhole\resolve_all;

class ChannelLayout extends Field
{
    public array $layouts;
    public Collection $configFields;
    public array $configModels = [];

    public function __construct(public ?Channel $model)
    {
        $this->layouts = resolve_all(PostLayouts::values());

        $this->configFields = collect($this->layouts)
            ->mapWithKeys(function ($layout) use ($model) {
                if ($field = $layout->configField()) {
                    return [
                        get_class($layout) => resolve($field, [
                            'model' => ($this->configModels[get_class($layout)] =
                                (object) ($model->layout_config[get_class($layout)] ?? [])),
                        ]),
                    ];
                }
            })
            ->filter();
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::cp.channel-layout-label') }}
                </div>

                <div class="stack gap-sm" data-controller="reveal">
                    <div class="btn-group">
                        @foreach ($layouts as $layout)
                            <div>
                                <input
                                    type="radio"
                                    name="layout"
                                    hidden
                                    id="layout_{{ get_class($layout) }}"
                                    value="{{ get_class($layout) }}"
                                    @checked(old('layout', $model->layout) === get_class($layout))
                                    data-reveal-target="if"
                                >
                                <label class="btn" for="layout_{{ get_class($layout) }}">
                                    @icon($layout->icon(), ['class' => 'text-md'])
                                    {{ $layout->label() }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @foreach ($configFields as $layoutClass => $field)
                        <div
                            class="card card__body"
                            data-reveal-target="then"
                            data-reveal-value="{{ $layoutClass }}"
                        >
                            @components([$field])
                        </div>
                    @endforeach
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'layout' => [Rule::in(array_map(fn($layout) => get_class($layout), $this->layouts))],
        ]);

        foreach ($this->configFields as $field) {
            $field->validating($validator);
        }
    }

    public function saving(FormRequest $request): void
    {
        $this->model->layout = $request->validated('layout');

        foreach ($this->configFields as $field) {
            $field->saving($request);
        }

        $this->model->layout_config = $this->configModels;
    }
}
