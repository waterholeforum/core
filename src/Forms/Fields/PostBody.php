<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Post;

class PostBody extends Field
{
    public function __construct(public ?Post $post)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="body"
                :label="__('waterhole::forum.post-body-label')"
            >
                <x-waterhole::text-editor
                    name="body"
                    :id="$component->id"
                    :value="old('body', $post->body ?? '')"
                    class="input"
                />
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['body' => ['required', 'string']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->post->body = $request->validated('body');
    }
}
