<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Post;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;

class PostTags extends Field
{
    public ?Collection $taxonomies;

    public function __construct(
        public ?Post $model,
    ) {
        $this->taxonomies = $model
            ->channel
            ?->taxonomies
            ->load('tags')
            ->filter(
                fn(Taxonomy $taxonomy) => (
                    Gate::allows('waterhole.taxonomy.assign-tags', $taxonomy)
                    && $taxonomy->tags->isNotEmpty()
                ),
            );
    }

    public function shouldRender(): bool
    {
        return $this->taxonomies?->isNotEmpty() ?? false;
    }

    public function render(): string
    {
        return <<<'blade'
                @foreach ($taxonomies as $taxonomy)
                    <x-waterhole::field
                        name="tag_ids.{{ $taxonomy->id }}*"
                        :label="__($taxonomy->name)"
                    >
                        <ui-combobox>
                            <select
                                name="tag_ids[{{ $taxonomy->id }}][]"
                                id="{{ $component->id }}"
                                @if ($taxonomy->allow_multiple) multiple @endif
                                @required($taxonomy->is_required)
                            >
                                @unless ($taxonomy->allow_multiple)
                                    <option value="">{{ __('waterhole::forum.post-tags-none-option') }}</option>
                                @endunless
                                @foreach ($taxonomy->tags as $tag)
                                    <option
                                        value="{{ $tag->id }}"
                                        @selected(in_array($tag->id, old("tag_ids.$taxonomy->id", $model->tags->modelKeys()) ?? []))
                                    >{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </ui-combobox>
                    </x-waterhole::field>
                @endforeach
            blade;
    }

    public function validating(Validator $validator): void
    {
        foreach ($this->taxonomies as $taxonomy) {
            $required = $taxonomy->is_required ? ['required'] : ['nullable'];

            $validator->addRules([
                "tag_ids.$taxonomy->id" => [
                    ...$required,
                    'array',
                    ...($taxonomy->allow_multiple ? [] : ['max:1']),
                ],
                "tag_ids.$taxonomy->id.*" => [
                    ...$required,
                    'integer',
                    Rule::exists(Tag::class, 'id')->where('taxonomy_id', $taxonomy->id),
                ],
            ]);
        }
    }

    public function saved(FormRequest $request): void
    {
        $this->model
            ->tags()
            ->detach($this->model->tags->filter(fn(Tag $tag) => $this->taxonomies->contains($tag->taxonomy_id)));

        $this->model
            ->tags()
            ->attach(
                $this->taxonomies
                    ->flatMap(fn(Taxonomy $taxonomy) => $request->validated(
                        "tag_ids.$taxonomy->id",
                        [],
                    ))
                    ->filter(),
            );

        $this->model->unsetRelation('tags');
    }
}
