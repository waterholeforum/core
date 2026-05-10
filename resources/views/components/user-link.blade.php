@blaze
@props(['user' => null, 'link' => true])

@if ($user && $link)
    <a href="{{ $user->url }}" {{ $attributes->merge(['data-turbo-frame' => '_top']) }}>
        {{ $slot }}
    </a>
@else
    <span {{ $attributes }}>{{ $slot }}</span>
@endif
