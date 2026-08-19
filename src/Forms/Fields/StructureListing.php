<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\StructureLink;

class StructureListing extends Field
{
    public function __construct(
        public Channel|Page|StructureLink $model,
    ) {}

    public function render(): string
    {
        return <<<'blade'
                <div role="group" class="field">
                    <div class="field__label">
                        {{ __('waterhole::cp.structure-discoverability-label') }}
                    </div>
                    <div>
                        <input type="hidden" name="is_listed" value="0">
                        <label class="choice">
                            <input
                                type="checkbox"
                                name="is_listed"
                                value="1"
                                @checked(old('is_listed', $model->structure?->is_listed ?? false))
                            >
                            <span class="stack gap-xxs">
                                <span>{{ __('waterhole::cp.structure-listed-label') }}</span>
                                <small class="field__description">{{ __('waterhole::cp.structure-listed-description') }}</small>
                            </span>
                        </label>
                    </div>
                </div>
            blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['is_listed' => ['nullable', 'boolean']]);
    }

    public function saved(FormRequest $request): void
    {
        if ($request->has('is_listed')) {
            $this->model->structure()->update(['is_listed' => $request->validated('is_listed')]);
        }
    }
}
