<a href="{{ route('waterhole.login') }}" class="btn btn--icon btn--transparent hide-md-up">
    @icon('tabler-user-circle')
    <ui-tooltip>{{ __('waterhole::forum.log-in') }}</ui-tooltip>
</a>

<div class="row hide-sm">
    <a
        href="{{ route('waterhole.login') }}"
        class="header-login btn btn--transparent btn--narrow color-accent"
    >
        {{ __('waterhole::forum.log-in') }}
    </a>

    @if (Route::has('waterhole.register'))
        <a
            href="{{ route('waterhole.register') }}"
            class="header-register btn btn--transparent btn--narrow color-accent"
        >
            {{ __('waterhole::forum.register') }}
        </a>
    @endif
</div>
