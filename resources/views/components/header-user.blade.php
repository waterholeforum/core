@auth
    <ui-popup placement="bottom-end" class="header-user">
        <a
            href="{{ Auth::user()->url }}"
            class="btn btn--icon"
            role="button"
            data-shortcut-trigger="navigation.user-menu"
        >
            <x-waterhole::avatar :user="Auth::user()" />
            <ui-tooltip>
                {{ Waterhole\username(Auth::user()) }}
                <x-waterhole::shortcut-label shortcut="navigation.user-menu" />
            </ui-tooltip>
        </a>

        <ui-menu class="menu" hidden data-shortcut-hidden>
            <h3 class="menu-heading">{{ Waterhole\username(Auth::user()) }}</h3>

            @components(\Waterhole\Extend\Ui\UserMenu::class)

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
