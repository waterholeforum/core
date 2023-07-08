@auth
    <ui-popup placement="bottom-end" class="header-user">
        <a href="{{ Auth::user()->url }}" class="btn btn--icon" role="button">
            <x-waterhole::avatar :user="Auth::user()" />
            <ui-tooltip>{{ Waterhole\username(Auth::user()) }}</ui-tooltip>
        </a>

        <ui-menu class="menu" hidden>
            <h3 class="menu-heading">{{ Waterhole\username(Auth::user()) }}</h3>

            @components(Waterhole\Extend\UserMenu::build())

            {{-- Disable Turbo as a means of clearing out the Drive cache --}}
            <form action="{{ route('waterhole.logout') }}" method="POST" data-turbo="false">
                @csrf
                <x-waterhole::menu-item
                    icon="tabler-logout"
                    :label="__('waterhole::user.log-out-link')"
                />
            </form>
        </ui-menu>
    </ui-popup>
@endauth
