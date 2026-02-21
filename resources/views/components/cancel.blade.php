<a
    href="{{ Waterhole\internal_url(old('return', request('return')), $default) }}"
    {{ $attributes->merge(['data-action' => 'modal#hide']) }}
>
    {{ __('waterhole::system.cancel-button') }}
</a>
