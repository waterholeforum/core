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

<a href="{{ route('waterhole.admin.updates') }}" class="nav-link {{ request()->routeIs('waterhole.admin.updates') ? 'is-active' : '' }}">
    <x-waterhole::icon icon="heroicon-o-refresh"/>
    <span class="label">Updates</span>
    <div class="spacer"></div>
    <turbo-frame
        id="updates_count"
        src="{{ route('waterhole.admin.updates.list') }}"
    >
        <div class="loading-indicator loading-indicator--inline"></div>
    </turbo-frame>
</a>

<br>

<div class="nav-text text-xs stack gap-xs">
    <p>
        <a href="https://waterhole.dev" class="color-muted" target="_blank">Waterhole {{ Waterhole::VERSION }}</a>
        <a href="#" class="badge">PRO</a>
    </p>
</div>
