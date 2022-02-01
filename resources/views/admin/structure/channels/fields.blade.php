<div class="panels">
    <details class="panel" open>
        <summary class="panel__header h4">Details</summary>

        <div class="panel__body form-groups" data-controller="slugger">
            <x-waterhole::field name="name" label="Name">
                <input
                    id="{{ $component->id }}"
                    name="name"
                    type="text"
                    value="{{ old('name', $channel->name ?? '') }}"
                    class="input"
                    data-action="slugger#updateName"
                >
            </x-waterhole::field>

            <x-waterhole::field name="slug" label="Slug">
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
                    This channel will be accessible at
                    {!! preg_replace('~^https?://~', '', str_replace('*', '<span data-slugger-target="mirror">'.old('slug', $channel->slug ?? '').'</span>', route('waterhole.channels.show', ['channel' => '*']))) !!}.
                </p>
            </x-waterhole::field>

            <x-waterhole::field name="icon" label="Icon">
                <x-waterhole::admin.icon-picker
                    name="icon"
                    :value="old('icon', $channel->icon ?? null)"
                />

{{--                <input--}}
{{--                    id="{{ $component->id }}"--}}
{{--                    name="icon"--}}
{{--                    type="text"--}}
{{--                    value="{{ old('icon', $channel->icon ?? '') }}"--}}
{{--                    class="input"--}}
{{--                >--}}
            </x-waterhole::field>

            <x-waterhole::field name="description" label="Description">
                <textarea
                    id="{{ $component->id }}"
                    name="description"
                    class="input"
                >{{ old('description', $channel->description ?? '') }}</textarea>
                <p class="field__description">
                    Describe what this channel is for.
                </p>
            </x-waterhole::field>

            <x-waterhole::field name="instructions" label="Posting Instructions">
                <textarea
                    id="{{ $component->id }}"
                    name="instructions"
                    class="input"
                >{{ old('instructions', $channel->instructions ?? '') }}</textarea>
                <p class="field__description">
                    Give instructions to be shown to users as they create posts in this
                    channel.
                </p>
            </x-waterhole::field>
        </div>
    </details>

    <details class="panel">
        <summary class="panel__header h4">Options</summary>

        <div class="panel__body form-groups">
            <div role="group" class="field">
                <div class="field__label">Visibility</div>
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
                        <span class="stack-xxs">
                            <span>Hide posts from Home</span>
                            <small class="field__description">Only show this channel's posts on its page.</small>
                        </span>
                    </label>
                </div>
            </div>

            <div role="group" class="field">
                <div class="field__label">Default Layout</div>
                <div class="stack-xs">
                    @foreach (['list' => ['List', 'heroicon-o-view-list'], 'cards' => ['Cards', 'heroicon-o-collection']] as $key => [$name, $icon])
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
                                {{ $name }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('default_layout')
                <div>{{ $message }}</div>
                @enderror
            </div>

            <div role="group" class="field">
                <div class="field__label">Filter Options</div>
                <div data-controller="reveal" class="stack-md">
                    <label class="choice">
                        <input
                            type="checkbox"
                            id="custom_filters"
                            name="custom_filters"
                            value="1"
                            data-reveal-target="if"
                            @if (old('custom_filters', $channel->filters ?? false)) checked @endif
                        >
                        <span class="stack-xxs">
                            <span>Use custom filter options</span>
                            <small class="field__description">Override the global filter options for this channel.</small>
                        </span>
                    </label>

                    <ul
                        data-reveal-target="then"
                        class="card admin-structure text-xs"
                        data-controller="dragon-nest"
                        data-dragon-nest-target="list"
                    >
                        @php
                            $filters = old('filters', $channel->filters ?? config('waterhole.forum.post_filters', []));
                        @endphp

                        @foreach (collect(Waterhole\Extend\PostFilters::values())->map(fn($class) => resolve($class))->sortBy(fn($filter, $key) => ($k = array_search($key, $filters)) === false ? INF : $k) as $filter)
                            @php $handle = $filter->handle(); @endphp
                            <li
                                class="admin-structure__node admin-structure__content toolbar"
                                draggable="true"
                            >
                                <x-waterhole::icon
                                    icon="heroicon-o-menu"
                                    class="color-muted admin-structure__handle js-only"
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

    <details class="panel">
        <summary class="panel__header h4">Permissions</summary>

        <div class="panel__body">
            <x-waterhole::admin.permission-grid
                :abilities="['view', 'comment', 'post', 'moderate']"
                :permissions="$channel->permissions ?? null"
                :defaults="['view', 'comment', 'post']"
            />
        </div>
    </details>
</div>
