<?php

namespace Waterhole\Taxonomy\Fields;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Post;
use Waterhole\Taxonomy\Tag;
use Waterhole\Taxonomy\Taxonomy;

class PostTags extends Field
{
    public ?Collection $taxonomies;

    public function __construct(public ?Post $model)
    {
        $this->taxonomies = $model->channel?->taxonomies
            ->load('tags')
            ->filter(
                fn(Taxonomy $taxonomy) => Gate::allows('taxonomy.assign-tags', $taxonomy) &&
                    $taxonomy->tags->count(),
            );
    }

    public function shouldRender(): bool
    {
        return (bool) $this->taxonomies;
    }

    public function render(): string
    {
        return <<<'blade'
            @foreach ($taxonomies as $taxonomy)
                <x-waterhole::field
                    name="tag_ids"
                    :label="$taxonomy->translated_name"
                >
                    <select
                        name="tag_ids[]"
                        id="{{ $component->id }}"
                        @if ($taxonomy->allow_multiple) multiple @endif
                    >
                        @unless ($taxonomy->allow_multiple) <option></option> @endunless
                        @foreach ($taxonomy->tags as $tag)
                            <option
                                value="{{ $tag->id }}"
                                @selected(in_array($tag->id, old('tag_ids', $model->tags->modelKeys())))
                            >{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </x-waterhole::field>
            @endforeach
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'tag_ids' => ['array'],
            'tag_ids.*' => [
                'required',
                'integer',
                Rule::exists(Tag::class, 'id')->whereIn(
                    'taxonomy_id',
                    $this->taxonomies->modelKeys(),
                ),
            ],
        ]);
    }

    public function saved(FormRequest $request): void
    {
        $this->model
            ->tags()
            ->detach(
                $this->model->tags->filter(
                    fn(Tag $tag) => $this->taxonomies->contains($tag->taxonomy_id),
                ),
            );
        $this->model->tags()->syncWithoutDetaching($request->validated('tag_ids'));
    }
}
