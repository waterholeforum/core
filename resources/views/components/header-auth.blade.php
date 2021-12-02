@auth
    <ui-popup placement="bottom-end" style="margin-left: var(--space-xs)">
        <button class="btn btn--icon">
            <x-waterhole::avatar :user="Auth::user()"/>
            <ui-tooltip>{{ Auth::user()->name }}</ui-tooltip>
        </button>
        <ui-menu class="menu" hidden>
            <h3 class="menu-heading">{{ Auth::user()->name }}</h3>

            <a href="{{ Auth::user()->url }}" class="menu-item" role="menuitem">
                <x-waterhole::icon icon="heroicon-o-user"/>
                <span>Profile</span>
            </a>

            <a href="{{ route('waterhole.preferences') }}" class="menu-item" role="menuitem">
                <x-waterhole::icon icon="heroicon-o-adjustments"/>
                <span>Preferences</span>
            </a>

            @if (config('waterhole.design.support_dark_mode'))
                <button class="menu-item js-only light-only" role="menuitem" data-action="theme#set" data-theme-name-param="dark">
                    <x-waterhole::icon icon="heroicon-o-moon"/>
                    <span>Dark Mode</span>
                </button>

                <button class="menu-item js-only dark-only" role="menuitem" data-action="theme#set" data-theme-name-param="light">
                    <x-waterhole::icon icon="heroicon-o-sun"/>
                    <span>Light Mode</span>
                </button>
            @endif

{{--            <a href="#" class="menu-item" role="menuitem">--}}
{{--                <x-waterhole::icon icon="heroicon-o-bell"/>--}}
{{--                <span>Followed Posts</span>--}}
{{--                <span class="badge">5</span>--}}
{{--            </a>--}}

            <hr class="menu-divider">

            <a href="{{ route('waterhole.admin.dashboard') }}" class="menu-item" role="menuitem">
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
