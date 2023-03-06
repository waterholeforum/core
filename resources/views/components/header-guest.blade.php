<div class="row">
    <a
        href="{{ route('waterhole.login') }}"
        class="header-login btn btn--transparent btn--narrow color-accent"
    >{{ __('waterhole::forum.log-in') }}</a>

    @if (Route::has('waterhole.register'))
        <a
            href="{{ route('waterhole.register') }}"
            class="header-register btn btn--transparent btn--narrow color-accent"
        >{{ __('waterhole::forum.register') }}</a>
    @endif
</div>
