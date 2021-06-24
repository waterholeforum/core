<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Extend\PostFilters;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;

class ChannelFilters extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::admin.channel-filter-options-label') }}
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
                            <span>{{ __('waterhole::admin.channel-custom-filters-label') }}</span>
                            <small class="field__description">{{ __('waterhole::admin.channel-custom-filters-description') }}</small>
                        </span>
                    </label>

                    <x-waterhole::admin.sortable-context data-reveal-target="then">
                        <ul
                            class="card sortable"
                            role="list"
                            data-sortable-target="container"
                            aria-label="{{ __('waterhole::admin.channel-filter-options-label') }}"
                        >
                            @php
                                $filters = old('filters', $model->filters ?? config('waterhole.forum.post_filters', []));

                                $availableFilters = collect(Waterhole\resolve_all(Waterhole\Extend\PostFilters::values()))
                                    ->sortBy(fn($filter, $key) => ($k = array_search($key, $filters)) === false ? INF : $k);
                            @endphp

                            @foreach ($availableFilters as $filter)
                                <li
                                    class="card__row row gap-md text-xs"
                                    aria-label="{{ $filter->label() }}"
                                >
                                    <button type="button" class="drag-handle" data-handle>
                                        <x-waterhole::icon icon="tabler-menu-2"/>
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
                    </x-waterhole::admin.sortable-context>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'filters' => ['required_with:custom_filters', 'array'],
            'filters.*' => ['string', 'distinct', Rule::in(PostFilters::values())],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->filters = $request->input('custom_filters')
            ? $request->validated('filters')
            : null;
    }
}
