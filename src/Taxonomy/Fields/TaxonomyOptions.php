<?php

namespace Waterhole\Taxonomy\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Taxonomy\Taxonomy;

class TaxonomyOptions extends Field
{
    public function __construct(public ?Taxonomy $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field">
                <div class="field__label">Options</div>

                <div class="stack gap-sm">
                    <label class="choice">
                        <input type="hidden" name="allow_multiple" value="0">
                        <input type="checkbox" name="allow_multiple" value="1" @checked($model->allow_multiple)>
                        Allow selection of multiple tags
                    </label>
                    <label class="choice">
                        <input type="hidden" name="show_on_post_summary" value="0">
                        <input type="checkbox" name="show_on_post_summary" value="1" @checked($model->show_on_post_summary)>
                        Show tags on post summaries
                    </label>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'allow_multiple' => ['boolean'],
            'show_on_post_summary' => ['boolean'],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->allow_multiple = $request->validated('allow_multiple');
        $this->model->show_on_post_summary = $request->validated('show_on_post_summary');
    }
}
