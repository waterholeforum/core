<div class="attribution">
    <a href="{{ $user?->url }}" class="attribution__user">
        <x-waterhole::avatar :user="$user"/>
        <span>{{ $user ? $user->name : 'Anonymous' }}</span>
    </a>
    <span class="attribution__info">
        <span>Member</span>
        @if ($date)
            <time datetime="{{ $date }}">{{ $date->diffForHumans() }}</time>
        @endif
    </span>
</div>
