<a
    href="{{ Waterhole\internal_url(old('return', request('return')), $default) }}"
    {{ $attributes->merge(['data-action' => 'modal#hide', 'data-shortcut-trigger' => 'navigation.close']) }}
>
    {{ __('waterhole::system.cancel-button') }}

    <ui-tooltip>
        {{ __('waterhole::system.cancel-button') }}
        <x-waterhole::shortcut-label shortcut="navigation.close" />
    </ui-tooltip>
</a>
