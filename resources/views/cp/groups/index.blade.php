<x-waterhole::cp :title="__('waterhole::cp.groups-title')">
    <div class="stack gap-lg">
        <div class="row gap-md">
            <h1 class="h3">{{ __('waterhole::cp.groups-title') }}</h1>

            <div class="grow"></div>

            <a
                href="{{ route('waterhole.cp.groups.create') }}"
                type="button"
                class="btn bg-accent"
            >
                @icon('tabler-plus')
                <span>{{ __('waterhole::cp.create-group-button') }}</span>
            </a>
        </div>

        @php
            $systemGroups = $groups->filter(fn ($group) => ! $group->isCustom());
            $customGroups = $groups->filter(fn ($group) => $group->isCustom())->sortBy('name');
        @endphp

        <ul class="card" role="list">
            @foreach ($systemGroups as $group)
                <x-waterhole::cp.group-row :group="$group" />
            @endforeach
        </ul>

        <ul class="card" role="list">
            @foreach ($customGroups as $group)
                <x-waterhole::cp.group-row :group="$group" />
            @endforeach
        </ul>
    </div>
</x-waterhole::cp>
