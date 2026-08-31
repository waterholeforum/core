<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Extend\Core\PostFilters;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

use function Waterhole\resolve_all;

class ChannelFilters extends Field
{
    public array $availableFilters;

    public function __construct(
        public ?Channel $model,
    ) {
        $this->availableFilters = array_values(array_filter(
            resolve_all(resolve(PostFilters::class)->values()),
            fn($filter) => $filter->availableFor($model),
        ));
    }

    public function render(): string
    {
        return <<<'blade'
                <div role="group" class="field">
                    <div class="field__label">
                        {{ __('waterhole::cp.channel-filters-label') }}
                    </div>
                    <div data-controller="reveal" class="stack gap-md">
                        <label class="choice">
                            <input
                                type="checkbox"
                                id="custom_filters"
                                name="custom_filters"
                                value="1"
                                data-reveal-target="if"
                                @checked(old('custom_filters', $model->filters ?? false))
                            >
                            <span class="stack gap-xxs">
                                <span>{{ __('waterhole::cp.channel-custom-filters-label') }}</span>
                                <small class="field__description">{{ __('waterhole::cp.channel-custom-filters-description') }}</small>
                            </span>
                        </label>

                        <div data-controller="sortable" data-reveal-target="then">
                            <ul
                                class="card sortable"
                                role="list"
                                data-sortable-target="container"
                                aria-label="{{ __('waterhole::cp.channel-filters-label') }}"
                            >
                                @php
                                    $filters = old('filters', $model->filters ?? config('waterhole.forum.post_filters', []));

                                    $availableFilters = collect($availableFilters)
                                        ->sortBy(fn($filter) => ($k = array_search(get_class($filter), $filters)) === false ? INF : $k);
                                @endphp

                                @foreach ($availableFilters as $filter)
                                    <li
                                        class="card__row row gap-md text-xs"
                                        aria-label="{{ $filter->label() }}"
                                        data-id="{{ $filter::class }}"
                                    >
                                        <button type="button" class="drag-handle" data-handle>
                                            @icon('tabler-grip-vertical')
                                        </button>

                                        <label class="choice">
                                            <input
                                                type="checkbox"
                                                name="filters[]"
                                                value="{{ $filter::class }}"
                                                @checked(in_array($filter::class, $filters))
                                            >
                                            {{ $filter->label() }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            blade;
    }

    public function validating(Validator $validator): void
    {
        $available = array_map(fn($filter) => $filter::class, $this->availableFilters);

        $validator->addRules([
            'filters' => ['required_with:custom_filters', 'array'],
            'filters.*' => ['string', 'distinct', Rule::in($available)],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        if (!$request->input('custom_filters')) {
            $this->model->filters = null;

            return;
        }

        $filters = $request->validated('filters');
        $available = array_map(fn($filter) => $filter::class, $this->availableFilters);
        $registered = resolve(PostFilters::class)->values();

        foreach ($this->model->getOriginal('filters') ?? [] as $position => $filter) {
            if (in_array($filter, $registered) && !in_array($filter, $available)) {
                array_splice($filters, min($position, count($filters)), 0, [$filter]);
            }
        }

        $this->model->filters = $filters;
    }
}
