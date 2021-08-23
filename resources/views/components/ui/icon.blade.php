@props(['icon'])

@php
    $attributes = $attributes->merge(['class' => 'icon']);
@endphp

@if (! empty($icon))
    @if (str_starts_with($icon, 'data:image/'))
        <img src="{{ $icon }}" alt="" {{ $attributes }}>
    @elseif (preg_match('/\.(svg|png|gif|jpg)$/i', $icon))
        <img src="{{ asset($icon) }}" alt="" {{ $attributes }}>
    @elseif (mb_strlen($icon) === 1)
        <span {{ $attributes }}>{{ emojify($icon) }}</span>
    @else
        {{ svg($icon, 'icon', $attributes->getAttributes()) }}
    @endif
@endif
