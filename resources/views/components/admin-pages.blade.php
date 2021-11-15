<a href="{{ route('waterhole.admin.home') }}" class="nav-link {{ request()->routeIs('waterhole.admin.home') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-chart-bar"/>
    <span class="label">Dashboard</span>
</a>

<a href="{{ route('waterhole.admin.settings') }}" class="nav-link {{ request()->routeIs('waterhole.admin.settings') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-cog"/>
    <span class="label">Settings</span>
</a>

<a href="{{ route('waterhole.admin.structure') }}" class="nav-link {{ request()->routeIs('waterhole.admin.structure*') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-collection"/>
    <span class="label">Structure</span>
</a>

<a href="{{ route('waterhole.admin.design') }}" class="nav-link {{ request()->routeIs('waterhole.admin.design') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-template"/>
    <span class="label">Design</span>
</a>

<a href="{{ route('waterhole.admin.users') }}" class="nav-link {{ request()->routeIs('waterhole.admin.users') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-users"/>
    <span class="label">Users</span>
</a>

<a href="{{ route('waterhole.admin.permissions') }}" class="nav-link {{ request()->routeIs('waterhole.admin.permissions') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-key"/>
    <span class="label">Permissions</span>
</a>

<a href="{{ route('waterhole.admin.utilities') }}" class="nav-link {{ request()->routeIs('waterhole.admin.utilities') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-terminal"/>
    <span class="label">Utilities</span>
</a>

<a href="{{ route('waterhole.admin.extensions') }}" class="nav-link {{ request()->routeIs('waterhole.admin.extensions') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-puzzle"/>
    <span class="label">Extensions</span>
</a>

<br>

<div class="nav-text text-xs stack-sm">
    <p><a href="" class="color-muted">Waterhole 0.1.0</a></p>
    <p><a href="" class="text-unread">2 Updates Available</a></p>
</div>
