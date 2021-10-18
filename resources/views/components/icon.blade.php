@props(['icon'])

@php
    $attributes = $attributes->class('icon');
@endphp

@if (! empty($icon))
    @if (str_starts_with($icon, 'data:image/'))
        <img src="{{ $icon }}" alt="" {{ $attributes }}>
    @elseif (preg_match('/\.(svg|png|gif|jpg)$/i', $icon))
        <img src="{{ asset($icon) }}" alt="" {{ $attributes }}>
    @elseif (mb_strlen($icon) === 1)
        {{ emojify($icon, $attributes->getAttributes()) }}
    @else
        {{ svg($icon, '', $attributes->class('icon-'.$icon)->getAttributes()) }}
    @endif
@endif
