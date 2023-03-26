<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\ReactionType;

class ReactionTypeScore extends Field
{
    public function __construct(public ?ReactionType $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="score"
                :label="__('waterhole::cp.reaction-type-score-label')"
                :description="__('waterhole::cp.reaction-type-score-description')"
            >
                <input
                    type="number"
                    name="score"
                    id="{{ $component->id }}"
                    value="{{ old('score', $model->score ?? 1) }}"
                    style="width: 10ch"
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['score' => ['required', 'integer']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->score = $request->validated('score');
    }
}
