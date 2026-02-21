<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;

class ReactionTypes extends Field
{
    public function __construct(public ?ReactionSet $model) {}

    public function shouldRender(): bool
    {
        return (bool) $this->model?->exists;
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="stack gap-md" data-controller="sortable">
                <ul
                    class="card sortable"
                    role="list"
                    data-sortable-target="container"
                    aria-label="{{ __('waterhole::cp.reaction-set-reactions-label') }}"
                >
                    @empty ($model->reactionTypes->load('reactionSet'))
                        <li class="placeholder">
                            {{ __('waterhole::cp.reaction-types-empty-message') }}
                        </li>
                    @else
                        @foreach ($model->reactionTypes->load('reactionSet') as $reactionType)
                            <li
                                class="card__row row gap-sm"
                                aria-label="{{ $reactionType->name }}"
                                data-id="{{ $reactionType->id }}"
                            >
                                <button type="button" class="drag-handle" data-handle>
                                    @icon('tabler-grip-vertical')
                                </button>

                                @icon($reactionType->icon)
                                {{ $reactionType->name }}
                                <span class="color-muted text-xs">
                                    {{ $reactionType->score > 0 ? '+' : '' }}{{ $reactionType->score }}
                                </span>

                                <input
                                    type="hidden"
                                    name="reaction_types_order[]"
                                    value="{{ $reactionType->id }}"
                                />

                                <x-waterhole::action-buttons
                                    class="row text-xs push-end -m-xxs"
                                    :for="$reactionType"
                                    :limit="2"
                                    context="cp"
                                />
                            </li>
                        @endforeach
                    @endempty
                </ul>

                <div>
                    <a
                        href="{{ route('waterhole.cp.reaction-sets.reaction-types.create', ['reactionSet' => $model]) }}"
                        class="btn"
                        data-turbo-frame="modal"
                    >
                        @icon('tabler-plus')
                        {{ __('waterhole::cp.reaction-types-add-button') }}
                    </a>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'reaction_types_order' => ['array'],
            'reaction_types_order.*' => [
                'integer',
                Rule::exists(ReactionType::class, 'id')->where(
                    'reaction_set_id',
                    $this->model->getKey(),
                ),
            ],
        ]);
    }

    public function saved(FormRequest $request): void
    {
        foreach ($request->validated('reaction_types_order') ?? [] as $position => $id) {
            ReactionType::where('reaction_set_id', $this->model->getKey())
                ->whereKey($id)
                ->update(compact('position'));
        }
    }
}
