@auth
    <ui-popup placement="bottom-end" style="margin-left: var(--space-sm)">
        <button class="btn btn--icon">
            <x-waterhole::avatar :user="Auth::user()"/>
            <ui-tooltip>{{ Auth::user()->name }}</ui-tooltip>
        </button>
        <ui-menu class="menu" hidden>
            <span class="menu-item">
                <span class="menu-item-title">{{ Auth::user()->name }}</span>
            </span>

            <hr class="menu-divider">

            <a href="#" class="menu-item" role="menuitem">
                <x-waterhole::icon icon="heroicon-o-user"/>
                <span>Profile</span>
            </a>

            <a href="#" class="menu-item" role="menuitem">
                <x-waterhole::icon icon="heroicon-o-adjustments"/>
                <span>Preferences</span>
            </a>

            <a href="#" class="menu-item" role="menuitem">
                <x-waterhole::icon icon="heroicon-o-bell"/>
                <span>Followed Posts</span>
                <span class="badge">5</span>
            </a>

            <hr class="menu-divider">

            <a href="{{ route('waterhole.admin.home') }}" class="menu-item" role="menuitem">
                <x-waterhole::icon icon="heroicon-o-cog"/>
                <span>Administration</span>
            </a>

            {{-- Disable Turbo as a means of clearing out the Drive cache --}}
            <form action="{{ route('waterhole.logout') }}" method="POST" data-turbo="false">
                @csrf
                <button type="submit" class="menu-item" role="menuitem">
                    <x-waterhole::icon icon="heroicon-o-logout"/>
                    <span>Log Out</span>
                </button>
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
