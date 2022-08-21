<div class="attribution">
    <span class="attribution__user">
        <x-waterhole::user-link :user="$user" class="attribution__name">
            <x-waterhole::avatar :user="$user"/>
            <span>{{ $user?->name ?? 'Anonymous' }}</span>
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
