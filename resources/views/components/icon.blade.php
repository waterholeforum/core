@php
    $attributes = $attributes->class('icon');
@endphp

@if (! empty($icon))
    @if (str_starts_with($icon, 'file:'))
        <img src="{{ Storage::disk('public')->url('icons/'.substr($icon, 5)) }}" alt="" {{ $attributes }}>
    @elseif (str_starts_with($icon, 'emoji:'))
        <span {{ $attributes }}>{{ emojify(substr($icon, 6)) }}</span>
    @else
        @php
            if (str_starts_with($icon, 'svg:')) {
                $icon = substr($icon, 4);
            }
            try {
                echo svg($icon, '', $attributes->class('icon-'.$icon)->getAttributes())->toHtml();
            } catch (BladeUI\Icons\Exceptions\SvgNotFound $e) {
                if (config('app.debug')) {
                    echo '<script>console.warn("Icon ['.e($icon).'] not found")</script>';
                }
            }
        @endphp
    @endif
@endif
