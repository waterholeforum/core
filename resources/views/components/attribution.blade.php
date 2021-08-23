@props(['user', 'date' => null])

<div class="attribution">
    <a href="{{ $user->url }}" class="attribution__user">
        <x-waterhole::ui.avatar :user="$user"/>
        <span>{{ $user->name }}</span>
    </a>
    <div class="attribution__info">
        <span>Member</span>
        @if ($date)
            <time datetime="{{ $date }}">{{ $date->diffForHumans() }}</time>
        @endif
    </div>
</div>
