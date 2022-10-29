<x-waterhole::admin :title="__('waterhole::admin.groups-title')">
    <div class="stack gap-md">
        <div class="row gap-md">
            <h1 class="h3">{{ __('waterhole::admin.groups-title') }}</h1>

            <div class="grow"></div>

            <a href="{{ route('waterhole.admin.groups.create') }}" type="button" class="btn bg-accent">
                <x-waterhole::icon icon="tabler-plus"/>
                <span>{{ __('waterhole::admin.create-group-button') }}</span>
            </a>
        </div>

        <ul class="card" role="list">
            @foreach ($groups as $group)
                <li class="card__row row gap-md">
                    <x-waterhole::group-badge
                        :group="$group"
                        class="text-xs"
                    />

                    <div class="grow"></div>

                    <a
                        href="{{ $group->users_url }}"
                        class="color-muted text-xs"
                    >{{ __('waterhole::admin.group-user-count', ['count' => $group->users_count]) }}</a>

                    <x-waterhole::action-menu
                        :for="$group"
                        placement="bottom-end"
                    />
                </li>
            @endforeach
        </ul>
    </div>
</x-waterhole::admin>
