<div class="attribution">
    <span class="attribution__user">
        <x-waterhole::user-link :user="$user" class="attribution__name">
            <x-waterhole::avatar :user="$user"/>

            @if ($user?->isOnline())
                <span class="dot bg-success">
                    <ui-tooltip>{{ __('waterhole::user.online-label') }}</ui-tooltip>
                </span>
            @endif

            <span>{{ Waterhole\username($user) }}</span>
        </x-waterhole::user-link>

        @foreach ($user->groups ?? [] as $group)
            @if ($group->is_public)
                <x-waterhole::group-label :group="$group" class="attribution__group"/>
            @endif
        @endforeach
    </span>

    <span class="attribution__info">
        @if ($user?->headline)
            <span>{{ $user->headline }}</span>
        @endif

        @if ($date)
            <span>
                @if ($permalink)
                    <a href="{{ $permalink }}" class="color-inherit" target="_top">
                @endif
                    <time datetime="{{ $date }}">{{ $date->diffForHumans() }}</time>
                @if ($permalink)
                    </a>
                @endif
            </span>
        @endif
    </span>
</div>
