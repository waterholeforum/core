<div class="attribution">
    <x-waterhole::user-link :user="$user" class="attribution__user">
        <x-waterhole::avatar :user="$user"/>
        <span>{{ $user?->name ?? 'Anonymous' }}</span>
    </x-waterhole::user-link>
    <span class="attribution__info">
        @if ($user?->headline)
            <span>{{ $user->headline }}</span>
        @endif
        @if ($date)
            <time datetime="{{ $date }}">{{ $date->diffForHumans() }}</time>
        @endif
    </span>
</div>
