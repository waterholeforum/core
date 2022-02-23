<x-waterhole::admin :title="__('waterhole::admin.groups-title')">
    <div class="stack gap-md">
        <div class="row gap-md">
            <h1 class="h2">{{ __('waterhole::admin.groups-title') }}</h1>

            <div class="spacer"></div>

            <a href="{{ route('waterhole.admin.groups.create') }}" type="button" class="btn btn--primary">
                <x-waterhole::icon icon="heroicon-s-plus"/>
                <span>{{ __('waterhole::admin.create-group-button') }}</span>
            </a>
        </div>

        <ul class="card" role="list">
            @foreach ($groups as $group)
                <li class="card__row row gap-md">
                    <x-waterhole::group-label
                        :group="$group"
                        class="text-xs"
                    />

                    <div class="spacer"></div>

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
