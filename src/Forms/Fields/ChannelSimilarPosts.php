<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelSimilarPosts extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::admin.channel-similar-posts-title') }}
                </div>
                <div>
                    <input type="hidden" name="show_similar_posts" value="0">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="show_similar_posts"
                            value="1"
                            @checked(old('show_similar_posts', $model->show_similar_posts ?? false))
                        >
                        <span class="stack gap-xxs">
                            <span>{{ __('waterhole::admin.channel-show-similar-posts-label') }}</span>
                        </span>
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['show_similar_posts' => ['nullable', 'boolean']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->show_similar_posts = $request->validated('show_similar_posts');
    }
}
