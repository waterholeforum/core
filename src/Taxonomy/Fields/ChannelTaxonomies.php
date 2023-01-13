<?php

namespace Waterhole\Taxonomy\Fields;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Taxonomy\Taxonomy;

class ChannelTaxonomies extends Field
{
    public Collection $taxonomies;

    public function __construct(public ?Channel $model)
    {
        $this->taxonomies = Taxonomy::all();
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::admin.channel-taxonomies-label') }}
                </div>

                <div class="card">
                    @foreach ($taxonomies as $taxonomy)
                        <label class="card__row choice">
                            <input
                                type="checkbox"
                                name="taxonomy_ids[]"
                                value="{{ $taxonomy->id }}"
                                @checked(in_array($taxonomy->id, old('taxonomy_ids', $model->taxonomies->modelKeys())))
                            >
                            <span>{{ $taxonomy->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'taxonomy_ids' => ['array'],
            'taxonomy_ids.*' => ['required', 'integer', 'exists:taxonomies,id'],
        ]);
    }

    public function saved(FormRequest $request): void
    {
        $this->model->taxonomies()->sync($request->validated('taxonomy_ids'));
    }
}
