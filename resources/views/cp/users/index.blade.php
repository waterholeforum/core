<x-waterhole::cp :title="__('waterhole::cp.users-title')">
    <div class="stack gap-md">
        <div class="row gap-sm wrap">
            <h1 class="h3">
                {{ __('waterhole::cp.users-title') }}
            </h1>

            <div class="grow"></div>

            <form
                class="combobox break-sm"
                data-controller="filter-input"
                data-turbo-action="replace"
                data-turbo-frame="users_frame"
            >
                <div class="input-container">
                    @icon('tabler-search', ['class' => 'no-pointer color-muted'])
                    <input
                        data-action="
                            incremental-search#input
                            focus->filter-input#focus
                            blur->filter-input#blur
                            filter-input#update
                        "
                        data-controller="incremental-search"
                        data-filter-input-target="input"
                        name="q"
                        placeholder="{{ __('waterhole::cp.users-filter-placeholder') }}"
                        type="search"
                        value="{{ request('q') }}"
                    />
                </div>

                <ul
                    class="menu combobox__list"
                    data-action="
                        combobox-commit->filter-input#commit
                        mousedown->filter-input#preventBlur
                    "
                    data-filter-input-target="list"
                    hidden
                    id="filter-suggestions"
                    role="listbox"
                >
                    <li id="filter-group" role="option" class="menu-item" data-value="group:">
                        <span class="menu-item__title">group:</span>
                        <span class="color-muted">
                            {{ __('waterhole::cp.users-filter-group-description') }}
                        </span>
                    </li>
                    @foreach (Waterhole\Models\Group::selectable()->get() as $group)
                        <li id="filter-group-{{ $group->id }}" role="option" class="menu-item">
                            <span class="menu-item__title">
                                group:{{ str_contains($group->name, ' ') ? '"' . $group->name . '"' : $group->name }}
                            </span>
                        </li>
                    @endforeach

                    <li role="option" class="menu-item" data-value="is:suspended">
                        <span class="menu-item__title">is:suspended</span>
                    </li>
                </ul>
            </form>

            <a href="{{ route('waterhole.cp.users.create') }}" type="button" class="btn bg-accent">
                @icon('tabler-plus')
                <span>{{ __('waterhole::cp.create-user-button') }}</span>
            </a>
        </div>

        <turbo-frame id="users_frame" target="_top" class="stack gap-md">
            @if ($users->isNotEmpty())
                <div class="card">
                    <div class="table-container full-width" tabindex="0">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach (['name', 'email', 'groups', 'created_at', 'last_seen_at'] as $column)
                                        <th>
                                            @if (in_array($column, $sortable))
                                                <a
                                                    href="{{ request()->fullUrlWithQuery([
                                                        'sort' => $column,
                                                        'direction' => $sort === $column ? ($direction === 'asc' ? 'desc' : 'asc') : null,
                                                        'page' => null,
                                                    ]) }}"
                                                    class="with-icon color-text"
                                                >
                                                    <span>
                                                        {{ __('waterhole::cp.users-' . str_replace('_', '-', $column) . '-column') }}
                                                    </span>
                                                    @if ($sort === $column)
                                                        @icon('tabler-chevron-' . ($direction === 'asc' ? 'up' : 'down'))
                                                    @endif
                                                </a>
                                            @else
                                                {{ __('waterhole::cp.users-' . str_replace('_', '-', $column) . '-column') }}
                                            @endif
                                        </th>
                                    @endforeach

                                    <th style="width: 1px"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <x-waterhole::user-label
                                                :user="$user"
                                                class="color-text"
                                                link
                                                target="_blank"
                                            />
                                        </td>

                                        <td>
                                            <a href="mailto:{{ $user->email }}">
                                                {{ Str::limit($user->email, 20) }}
                                            </a>
                                        </td>

                                        <td>
                                            <x-waterhole::user-groups :user="$user" />
                                        </td>

                                        <td>
                                            <x-waterhole::relative-time
                                                :datetime="$user->created_at"
                                            />
                                        </td>

                                        <td>
                                            <x-waterhole::relative-time
                                                :datetime="$user->last_seen_at"
                                            />
                                        </td>

                                        <td>
                                            <x-waterhole::action-buttons
                                                :for="$user"
                                                :limit="2"
                                                context="cp"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $users->withQueryString()->links('waterhole::pagination.default') }}
                </div>
            @else
                <div class="placeholder card">
                    @icon('tabler-search', ['class' => 'placeholder__icon'])
                    <h4>{{ __('waterhole::cp.users-empty-message') }}</h4>
                </div>
            @endif
        </turbo-frame>
    </div>
</x-waterhole::cp>
