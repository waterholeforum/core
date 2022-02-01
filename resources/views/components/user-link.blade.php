@if ($user)
    <a href="{{ $user->url }}" {{ $attributes->merge(['data-turbo-frame' => '_top']) }}>{{ $slot }}</a>
@else
    <span {{ $attributes }}>{{ $slot }}</span>
@endif
