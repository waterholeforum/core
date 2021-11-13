@if ($user)
    <a href="{{ $user->url }}" {{ $attributes }}>{{ $slot }}</a>
@else
    <span {{ $attributes }}>{{ $slot }}</span>
@endif
