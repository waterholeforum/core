<a href="{{ route('waterhole.admin.dashboard') }}" class="nav-link {{ request()->routeIs('waterhole.admin.dashboard') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-chart-square-bar"/>
    <span class="label">Dashboard</span>
</a>

<a href="{{ route('waterhole.admin.structure') }}" class="nav-link {{ request()->routeIs('waterhole.admin.structure*') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-collection"/>
    <span class="label">Structure</span>
</a>

<a href="{{ route('waterhole.admin.users.index') }}" class="nav-link {{ request()->routeIs('waterhole.admin.users*') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-user"/>
    <span class="label">Users</span>
</a>

<a href="{{ route('waterhole.admin.groups.index') }}" class="nav-link {{ request()->routeIs('waterhole.admin.groups*') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-user-group"/>
    <span class="label">Groups</span>
</a>

<a href="{{ route('waterhole.admin.extensions') }}" class="nav-link {{ request()->routeIs('waterhole.admin.extensions') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-puzzle"/>
    <span class="label">Extensions</span>
</a>

<a href="{{ route('waterhole.admin.extensions') }}" class="nav-link {{ request()->routeIs('waterhole.admin.extensions') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-refresh"/>
    <span class="label">Updates</span>
    <span class="badge badge--unread">2</span>
</a>

<br>

<div class="nav-text text-xs stack-xs">
    <p><a href="" class="color-muted">Waterhole {{ Waterhole::VERSION }}</a> <span class="badge">PRO</span></p>
</div>
