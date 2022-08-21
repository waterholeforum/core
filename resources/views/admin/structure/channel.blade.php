@php
    $title = isset($channel)
        ? __('waterhole::admin.edit-channel-title')
        : __('waterhole::admin.create-channel-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        :parent-title="__('waterhole::admin.structure-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($channel) ? route('waterhole.admin.structure.channels.update', compact('channel')) : route('waterhole.admin.structure.channels.store') }}"
    >
        @csrf
        @if (isset($channel)) @method('PATCH') @endif

        <div class="stack gap-lg">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                <details class="card" open>
                    <summary class="card__header h5">
                        {{ __('waterhole::admin.channel-details-title') }}
                    </summary>

                    <div class="card__body form-groups" data-controller="slugger">
                        <x-waterhole::field
                            name="name"
                            :label="__('waterhole::admin.channel-name-label')"
                        >
                            <input
                                id="{{ $component->id }}"
                                name="name"
                                type="text"
                                value="{{ old('name', $channel->name ?? '') }}"
                                class="input"
                                data-action="slugger#updateName"
                            >
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="slug"
                            :label="__('waterhole::admin.channel-name-label')"
                        >
                            <input
                                id="{{ $component->id }}"
                                name="slug"
                                type="text"
                                value="{{ old('slug', $channel->slug ?? '') }}"
                                class="input"
                                data-action="slugger#updateSlug"
                                data-slugger-target="slug"
                            >
                            <p class="field__description">
                                {{ __('waterhole::admin.channel-slug-url-label') }}
                                {!! preg_replace('~^https?://~', '', str_replace('*', '<span data-slugger-target="mirror">'.old('slug', $channel->slug ?? '').'</span>', route('waterhole.channels.show', ['channel' => '*']))) !!}
                            </p>
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="icon"
                            :label="__('waterhole::admin.channel-icon-label')"
                        >
                            <x-waterhole::admin.icon-picker
                                name="icon"
                                :value="old('icon', $channel->icon ?? null)"
                            />
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="description"
                            :label="__('waterhole::admin.channel-description-label')"
                        >
                            <textarea
                                id="{{ $component->id }}"
                                name="description"
                                class="input"
                            >{{ old('description', $channel->description ?? '') }}</textarea>
                            <p class="field__description">
                                {{ __('waterhole::admin.channel-description-description') }}
                            </p>
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="instructions"
                            :label="__('waterhole::admin.channel-instructions-label')"
                        >
                            <textarea
                                id="{{ $component->id }}"
                                name="instructions"
                                class="input"
                            >{{ old('instructions', $channel->instructions ?? '') }}</textarea>
                            <p class="field__description">
                                {{ __('waterhole::admin.channel-instructions-description') }}
                            </p>
                        </x-waterhole::field>
                    </div>
                </details>

                <details class="card">
                    <summary class="card__header h5">
                        {{ __('waterhole::admin.channel-options-title') }}
                    </summary>

                    <div class="card__body form-groups">
                        <div role="group" class="field">
                            <div class="field__label">
                                {{ __('waterhole::admin.channel-visibility-label') }}
                            </div>
                            <div>
                                <input type="hidden" name="sandbox" value="0">
                                <label class="choice">
                                    <input
                                        type="checkbox"
                                        id="sandbox"
                                        name="sandbox"
                                        value="1"
                                        @if (old('sandbox', $channel->sandbox ?? false)) checked @endif
                                    >
                                    <span class="stack gap-xxs">
                                        <span>{{ __('waterhole::admin.channel-sandbox-label') }}</span>
                                        <small class="field__description">{{ __('waterhole::admin.channel-sandbox-description') }}</small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div role="group" class="field">
                            <div class="field__label">
                                {{ __('waterhole::admin.channel-default-layout-label') }}
                            </div>
                            <div class="stack gap-xs">
                                @foreach (['list' => 'heroicon-o-view-list', 'cards' => 'heroicon-o-collection'] as $key => $icon)
                                    <label class="choice">
                                        <input
                                            type="radio"
                                            name="default_layout"
                                            id="default_layout_{{ $key }}"
                                            value="{{ $key }}"
                                            @if (old('default_layout', $channel->default_layout ?? config('waterhole.forum.default_layout')) === $key) checked @endif
                                        >
                                        <span class="with-icon">
                                            <x-waterhole::icon :icon="$icon"/>
                                            {{ __("waterhole::system.layout-$key") }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

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
                                        @if (old('custom_filters', $channel->filters ?? false)) checked @endif
                                    >
                                    <span class="stack gap-xxs">
                                        <span>{{ __('waterhole::admin.channel-custom-filters-label') }}</span>
                                        <small class="field__description">{{ __('waterhole::admin.channel-custom-filters-description') }}</small>
                                    </span>
                                </label>

                                <ul
                                    data-reveal-target="then"
                                    class="card text-xs"
                                    data-controller="dragon-nest"
                                    data-dragon-nest-target="list"
                                >
                                    @php
                                        $filters = old('filters', $channel->filters ?? config('waterhole.forum.post_filters', []));

                                        $availableFilters = collect(Waterhole\resolve_all(Waterhole\Extend\PostFilters::values()))
                                            ->sortBy(fn($filter, $key) => ($k = array_search($key, $filters)) === false ? INF : $k);
                                    @endphp

                                    @foreach ($availableFilters as $filter)
                                        <li
                                            class="card__row row gap-md"
                                            draggable="true"
                                        >
                                            <x-waterhole::icon
                                                icon="heroicon-o-menu"
                                                class="drag-handle"
                                                data-handle
                                            />

                                            <label class="choice">
                                                <input
                                                    type="checkbox"
                                                    name="filters[]"
                                                    value="{{ $filter::class }}"
                                                    @if (in_array($filter::class, $filters)) checked @endif
                                                >
                                                {{ $filter->label() }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </details>

                <details class="card">
                    <summary class="card__header h5">
                        {{ __('waterhole::admin.channel-permissions-title') }}
                    </summary>

                    <div class="card__body">
                        <x-waterhole::admin.permission-grid
                            :abilities="['view', 'comment', 'post', 'moderate']"
                            :permissions="$channel->permissions ?? null"
                            :defaults="['view', 'comment', 'post']"
                        />
                    </div>
                </details>
            </div>

            <div>
                <div class="row gap-xs wrap">
                    <button
                        type="submit"
                        class="btn bg-accent btn--wide"
                    >
                        {{ isset($channel) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                    </button>

                    <a
                        href="{{ route('waterhole.admin.structure') }}"
                        class="btn"
                    >{{ __('waterhole::system.cancel-button') }}</a>
                </div>
            </div>
        </div>
    </form>
</x-waterhole::admin>
