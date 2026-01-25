<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelApproval extends Field
{
    public function __construct(public ?Channel $model) {}

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::cp.channel-approval-label') }}
                </div>
                <div class="stack gap-sm">
                    <input type="hidden" name="require_approval_posts" value="0">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="require_approval_posts"
                            value="1"
                            @checked(old('require_approval_posts', $model->require_approval_posts ?? false))
                        >
                        <span>{{ __('waterhole::cp.channel-require-approval-posts-label') }}</span>
                    </label>
                    <input type="hidden" name="require_approval_comments" value="0">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="require_approval_comments"
                            value="1"
                            @checked(old('require_approval_comments', $model->require_approval_comments ?? false))
                        >
                        <span>{{ __('waterhole::cp.channel-require-approval-comments-label') }}</span>
                    </label>
                    <div class="field__description">
                        {{ __('waterhole::cp.channel-approval-moderators-exempt') }}
                    </div>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'require_approval_posts' => ['nullable', 'boolean'],
            'require_approval_comments' => ['nullable', 'boolean'],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->require_approval_posts = $request->validated('require_approval_posts');
        $this->model->require_approval_comments = $request->validated('require_approval_comments');
    }
}
