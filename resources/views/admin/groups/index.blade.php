<x-waterhole::admin title="Groups">
    <div class="stack-md">
        <div class="toolbar">
            <h1 class="h2">Groups</h1>

            <div class="spacer"></div>

            <a href="{{ route('waterhole.admin.groups.create') }}" type="button" class="btn btn--primary">
                <x-waterhole::icon icon="heroicon-s-plus"/>
                <span>Create Group</span>
            </a>
        </div>

        <ul
            class="card admin-structure"
            role="list"
        >
            @foreach ($groups as $group)
                <li class="admin-structure__content toolbar">
                    <x-waterhole::group-label :group="$group"/>
                    <div class="spacer"></div>
                    <a
                        href="{{ route('waterhole.admin.users.index', ['q' => 'group:'.(str_contains($group->name, ' ') ? '"'.$group->name.'"' : $group->name)]) }}"
                        class="color-muted text-xs"
                    >{{ $group->users_count }} users</a>
                    <x-waterhole::action-menu :for="$group" placement="bottom-end" context="admin"/>
                </li>
            @endforeach
        </ul>
    </div>
</x-waterhole::admin>
