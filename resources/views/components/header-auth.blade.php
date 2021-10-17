@auth
    <ui-popup placement="bottom-end">
        <button class="btn btn--icon">
            <x-waterhole::avatar :user="Auth::user()"/>
        </button>
        <ui-menu class="menu" hidden>
            <form action="{{ route('waterhole.logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item">Log Out</button>
            </form>
        </ui-menu>
    </ui-popup>
@else
    <a
        href="{{ route('waterhole.login') }}"
        class="btn btn--link"
    >{{ __('waterhole::header.log-in') }}</a>
    <a
        href="{{ route('waterhole.register') }}"
        class="btn btn--link"
    >{{ __('waterhole::header.register') }}</a>
@endauth
