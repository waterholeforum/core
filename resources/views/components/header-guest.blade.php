@guest
    <a
        href="{{ route('waterhole.login') }}"
        class="btn btn--transparent btn--narrow color-accent"
    >{{ __('waterhole::header.log-in') }}</a>

    <a
        href="{{ route('waterhole.register') }}"
        class="btn btn--transparent btn--narrow color-accent"
    >{{ __('waterhole::header.register') }}</a>
@endguest
