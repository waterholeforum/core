<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Models\Taxonomy;

class ChannelTaxonomies extends Field
{
    public Collection $taxonomies;

    public function __construct(public ?Channel $model)
    {
        $this->taxonomies = Taxonomy::all();
    }

    public function shouldRender(): bool
    {
        return $this->taxonomies->isNotEmpty();
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label with-icon">
                    @icon('tabler-tags', ['class' => 'text-md'])
                    {{ __('waterhole::cp.channel-taxonomies-label') }}
                </div>

                <div class="card">
                    @foreach ($taxonomies as $taxonomy)
                        <div class="card__row">
                            <label class="choice">
                                <input
                                    type="checkbox"
                                    name="taxonomy_ids[]"
                                    value="{{ $taxonomy->id }}"
                                    @checked(in_array($taxonomy->id, old('taxonomy_ids', $model->taxonomies->modelKeys())))
                                >
                                <span>{{ $taxonomy->name }}</span>
                            </label>
                        </div>
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
