<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\ReactionSet;

class ReactionSetDefaults extends Field
{
    public function __construct(public ?ReactionSet $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field">
                <div class="field__label">
                {{ __('waterhole::cp.reaction-usage-label') }}
                </div>

                <div class="stack gap-sm">
                    <label class="choice">
                        <input type="hidden" name="is_default_posts" value="0">
                        <input type="checkbox" name="is_default_posts" value="1" @checked($model->is_default_posts)>
                        {{ __('waterhole::cp.reaction-default-posts') }}
                    </label>
                    <label class="choice">
                        <input type="hidden" name="is_default_comments" value="0">
                        <input type="checkbox" name="is_default_comments" value="1" @checked($model->is_default_comments)>
                        {{ __('waterhole::cp.reaction-default-comments') }}
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'is_default_posts' => ['boolean'],
            'is_default_comments' => ['boolean'],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->is_default_posts = $request->validated('is_default_posts');
        $this->model->is_default_comments = $request->validated('is_default_comments');
    }

    public function saved(FormRequest $request): void
    {
        if ($this->model->is_default_posts) {
            $update['is_default_posts'] = false;
        }
        if ($this->model->is_default_comments) {
            $update['is_default_comments'] = false;
        }

        if (isset($update)) {
            ReactionSet::where('id', '!=', $this->model->id)->update($update);
        }
    }
}
