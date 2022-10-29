<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Post;

class PostTitle extends Field
{
    public function __construct(public ?Post $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="title"
                :label="__('waterhole::forum.post-title-label')"
            >
                <input
                    id="{{ $component->id }}"
                    name="title"
                    type="text"
                    value="{{ old('title', $model->title ?? '') }}"
                    class="input"
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['title' => ['required', 'string', 'max:255']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->title = $request->validated('title');
    }
}
