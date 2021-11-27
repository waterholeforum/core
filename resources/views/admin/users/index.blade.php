<x-waterhole::admin title="Users">
    <div class="stack-md">

        <div class="toolbar">
            <h1 class="h2">Users</h1>

            <div class="spacer"></div>

            <form
                data-controller="filter-input"
                data-turbo-frame="users_frame"
                data-turbo-action="replace"
                class="combobox"
            >
                <div class="input-container">
                    <x-waterhole::icon
                        icon="heroicon-o-search"
                        class="pointer-events-none"
                    />
                    <input
                        class="input"
                        data-controller="incremental-search"
                        data-filter-input-target="input"
                        data-action="input->incremental-search#input focus->filter-input#focus blur->filter-input#blur input->filter-input#update"
                        name="q"
                        placeholder="Filter users"
                        type="search"
                        value="{{ request('q') }}"
                    >
                </div>

                <ul role="listbox" id="filter-suggestions" hidden data-filter-input-target="list" class="menu combobox__list" data-action="combobox-commit->filter-input#commit mousedown->filter-input#preventBlur">
                    <li id="filter-group" role="option" class="menu-item" data-value="group:">
                        <span class="menu-item-title">group:</span>
                        <span class="color-muted">Filter by group</span>
                    </li>
                    @foreach (Waterhole\Models\Group::selectable()->get() as $group)
                        <li id="filter-group-{{ $group->id }}" role="option" class="menu-item">
                            <span class="menu-item-title">group:{{ str_contains($group->name, ' ') ? '"'.$group->name.'"' : $group->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </form>

            <a href="{{ route('waterhole.admin.users.create') }}" type="button" class="btn btn--primary">
                <x-waterhole::icon icon="heroicon-s-plus"/>
                <span>Create User</span>
            </a>
        </div>

        <turbo-frame id="users_frame" target="_top" class="stack-md">
            @if ($users->isNotEmpty())
                <div class="table-container full-width">
                    <table class="table">
                        <thead>
                            <tr>
{{--                                <td class="choice-cell">--}}
{{--                                    <label class="choice">--}}
{{--                                        <input type="checkbox">--}}
{{--                                    </label>--}}
{{--                                </td>--}}
                                @foreach (['name', 'email', 'groups', 'created_at', 'last_seen_at'] as $column)
                                    <th>
                                        @if (in_array($column, $sortable))
                                            <a
                                                href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => $sort === $column ? ($direction === 'asc' ? 'desc' : 'asc') : null]) }}"
                                                class="with-icon color-text"
                                            >
                                                <span>{{ Str::headline($column) }}</span>
                                                @if ($sort === $column)
                                                    <x-waterhole::icon icon="heroicon-s-chevron-{{ $direction === 'asc' ? 'up' : 'down' }}"/>
                                                @endif
                                            </a>
                                        @else
                                            {{ Str::headline($column) }}
                                        @endif
                                    </th>
                                @endforeach
                                <th style="width: 1px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
{{--                                    <td class="choice-cell">--}}
{{--                                        <label class="choice">--}}
{{--                                            <input type="checkbox">--}}
{{--                                        </label>--}}
{{--                                    </td>--}}
                                    <td>
                                        <x-waterhole::user-label :user="$user" link class="color-text" target="_blank"/>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}">{{ Str::limit($user->email, 20) }}</a>
                                    </td>
                                    <td>
                                        @foreach ($user->groups as $group)
                                            <x-waterhole::group-label :group="$group"/>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ time_ago($user->created_at) }}
                                    </td>
                                    <td>
                                        {{ time_ago($user->last_seen_at) }}
                                    </td>
                                    <td>
                                        <x-waterhole::action-menu :for="$user" placement="bottom-end" context="admin"/>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $users->links() }}
                </div>
            @else
                <div class="placeholder card">
                    <x-waterhole::icon icon="heroicon-o-search" class="placeholder__visual"/>
                    <h3>No Results Found</h3>
                </div>
            @endif
        </turbo-frame>
    </div>
</x-waterhole::admin>
