@if ($user?->avatar_url)
    <img
        src="{{ $user->avatar_url }}"
        alt="{{ $user->name }}"
        {{ $attributes->class('avatar') }}
    >
@else
    <svg
        {{ $attributes->class(['avatar', 'avatar--anonymous' => empty($user)]) }}
        viewBox="0 0 100 100"
    >
        <rect width="100%" height="100%" fill="{{ $color() }}"/>
        <text
            x="50%"
            y="50%"
            dominant-baseline="central"
            text-anchor="middle"
        >
            {{ $user?->name ? strtoupper($user->name[0]) : '?' }}
        </text>
    </svg>
@endif
