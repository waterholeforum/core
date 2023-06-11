<x-waterhole::cp :title="__('waterhole::cp.groups-title')">
    <div class="stack gap-md">
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

        <ul class="card" role="list">
            @foreach ($groups as $group)
                <li class="card__row row gap-md">
                    <x-waterhole::group-badge :group="$group" class="text-xs" />

                    <div class="grow"></div>

                    <a href="{{ $group->users_url }}" class="color-muted text-xs">
                        {{ __('waterhole::cp.group-user-count', ['count' => $group->users_count]) }}
                    </a>

                    <x-waterhole::action-buttons
                        :for="$group"
                        :limit="2"
                        context="cp"
                        class="text-xs"
                    />
                </li>
            @endforeach
        </ul>
    </div>
</x-waterhole::cp>
