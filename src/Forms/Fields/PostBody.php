<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Post;

class PostBody extends Field
{
    public function __construct(public ?Post $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            @php
                $label = __([
                    "waterhole.channel-{$model->channel->slug}-post-body-label",
                    'waterhole::forum.post-body-label',
                ]);

                $description = __([
                    "waterhole.channel-{$model->channel->slug}-post-body-description",
                    '',
                ]);
            @endphp

            <x-waterhole::field id="post-body" name="body" :$label :$description>
                <x-waterhole::text-editor
                    name="body"
                    :id="$component->id"
                    :value="old('body', $model->body ?? '')"
                    style="min-height: 50vh"
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
        $this->model->body = $request->validated('body');
    }
}
