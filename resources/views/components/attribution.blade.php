<div class="attribution">
    <span class="attribution__user">
        <x-waterhole::user-link :user="$user" class="attribution__name">
            <x-waterhole::avatar :user="$user"/>

            @if ($user?->isOnline())
                <span class="dot color-success">
                    <ui-tooltip>{{ __('waterhole::user.online-label') }}</ui-tooltip>
                </span>
            @endif

            <span>{{ Waterhole\username($user) }}</span>
        </x-waterhole::user-link>

        <x-waterhole::user-groups :user="$user"/>
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
