@if (isset($user) && $user->avatarUrl)
  <img
    src="{{ $user->avatarUrl }}"
    alt="{{ $user->displayName }}"
    {{ $attributes->merge(['class' => 'Avatar']) }}
  >
@else
  <svg
    {{ $attributes->merge(['class' => 'Avatar'.(empty($user) ? ' Avatar--anonymous' : '')]) }}
    viewBox="0 0 100 100"
  >
    <rect width="100%" height="100%" fill="{{ $color }}"/>
    <text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" font-size="50px">
      {{ isset($user) ? strtoupper($user->displayName[0]) : '?' }}
    </text>
  </svg>
@endif
