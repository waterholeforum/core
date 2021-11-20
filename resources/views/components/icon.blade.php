@php
    $attributes = $attributes->class('icon');
@endphp

@if (! empty($icon))
    @if (str_starts_with($icon, 'data:image/'))
        <img src="{{ $icon }}" alt="" {{ $attributes }}>
    @elseif (preg_match('/\.(svg|png|gif|jpg)$/i', $icon))
        <img src="{{ asset($icon) }}" alt="" {{ $attributes }}>
    @elseif (str_starts_with($icon, 'emoji:'))
        <span {{ $attributes }}>{{ emojify(substr($icon, 6)) }}</span>
    @else
        @php
            try {
                echo svg($icon, '', $attributes->class('icon-'.$icon)->getAttributes())->toHtml();
            } catch (BladeUI\Icons\Exceptions\SvgNotFound $e) {
                //
            }
        @endphp
    @endif
@endif
