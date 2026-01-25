<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Group;

class GroupRules extends Field
{
    public function __construct(public ?Group $model) {}

    public function render(): string
    {
        return <<<'blade'
            <div class="field">
                <div class="field__label">{{ __('waterhole::cp.group-rules-title') }}</div>

                <div class="stack gap-sm">
                    <div>
                        <input type="hidden" name="auto_assign" value="0">
                        <label class="choice">
                            <input
                                type="checkbox"
                                name="auto_assign"
                                value="1"
                                @checked(old('auto_assign', $model->auto_assign ?? null))
                            >
                            {{ __('waterhole::cp.group-auto-assign-label') }}
                        </label>
                    </div>

                    <div class="stack gap-sm" data-controller="reveal">
                        <div>
                            <input type="hidden" name="rules[requires_approval]" value="0">
                            <label class="choice">
                                <input
                                    data-reveal-target="if"
                                    type="checkbox"
                                    name="rules[requires_approval]"
                                    value="1"
                                    @checked(old('rules.requires_approval', $model?->rules['requires_approval'] ?? false))
                                >
                                {{ __('waterhole::cp.group-rules-requires-approval-label') }}
                            </label>
                        </div>

                        <div class="choice-indent">
                            <input type="hidden" name="rules[remove_after_approval]" value="0">
                            <label class="choice" data-reveal-target="then">
                                <input
                                    type="checkbox"
                                    name="rules[remove_after_approval]"
                                    value="1"
                                    @checked(old('rules.remove_after_approval', $model?->rules['remove_after_approval'] ?? false))
                                >
                                {{ __('waterhole::cp.group-rules-remove-after-approval-label') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'auto_assign' => ['boolean'],
            'rules' => ['array'],
            'rules.requires_approval' => ['boolean'],
            'rules.remove_after_approval' => ['boolean'],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->auto_assign = $request->validated('auto_assign');

        $requiresApproval = $request->boolean('rules.requires_approval');

        $this->model->rules = [
            'requires_approval' => $requiresApproval,
            'remove_after_approval' =>
                $requiresApproval && $request->boolean('rules.remove_after_approval'),
        ];
    }
}
