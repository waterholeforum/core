<x-waterhole::layout :title="$title">
    <div class="section container">
        <div style="display: flex; align-items: flex-start; gap: var(--space-xl)">
            <x-waterhole::avatar :user="$user" style="width: 12ch"/>
            <div style="flex-grow: 1" class="stack-xs">
                <h1 class="h1" data-page-target="title">
                    {{ $user->name }}
                </h1>
                @if ($user->headline)
                    <p class="h3">{{ $user->headline }}</p>
                @endif
                @if ($user->bio)
                    <p class="content">{{ $user->bio }}</p>
                @endif
                <div class="toolbar toolbar--baseline color-muted text-xs">
                    @if ($user->groups->where('is_public')->count())
                        <span>
                            @foreach ($user->groups as $group)
                                @if ($group->is_public)
                                    <x-waterhole::group-label :group="$group"/>
                                @endif
                            @endforeach
                        </span>
                    @endif
                    @if ($user->location)
                        <span class="with-icon">
                            <x-waterhole::icon icon="heroicon-o-location-marker"/>
                            <span>{{ $user->location }}</span>
                        </span>
                    @endif
                    @if ($user->website)
                        <a href="{{ $user->website }}" class="with-icon color-muted">
                            <x-waterhole::icon icon="heroicon-o-link"/>
                            <span>{{ $user->website }}</span>
                        </a>
                    @endif
                    @if ($user->created_at)
                        <span class="with-icon">
                            <x-waterhole::icon icon="heroicon-o-calendar"/>
                            <span>Joined {{ $user->created_at->format('M Y') }}</span>
                        </span>
                    @endif
                </div>
            </div>
            <div class="toolbar">
                <button class="btn">
                    <x-waterhole::icon icon="heroicon-o-cog"/>
                    <span>Controls</span>
                    <x-waterhole::icon icon="heroicon-s-chevron-down"/>
                </button>
            </div>
        </div>
    </div>

    <div class="section container with-sidebar-start">
        <div class="sidebar--sticky">
            <nav class="nav">
                <a
                    href="{{ route('waterhole.user.posts', compact('user')) }}"
                    class="nav-link @if (request()->routeIs('waterhole.user.posts')) is-active @endif"
                >
                    <x-waterhole::icon icon="heroicon-o-collection"/>
                    <span>Posts</span>
                    <span class="badge">{{ $user->posts_count }}</span>
                </a>

                <a
                    href="{{ route('waterhole.user.comments', compact('user')) }}"
                    class="nav-link @if (request()->routeIs('waterhole.user.comments')) is-active @endif"
                >
                    <x-waterhole::icon icon="heroicon-o-chat-alt"/>
                    <span>Comments</span>
                    <span class="badge">{{ $user->comments_count }}</span>
                </a>

                @if ($user->is(Auth::user()))
                    <h3 class="nav-heading">Preferences</h3>

                    <a
                        href="{{ route('waterhole.preferences.account') }}"
                        class="nav-link @if (request()->routeIs('waterhole.preferences.account')) is-active @endif"
                    >
                        <x-waterhole::icon icon="heroicon-o-finger-print"/>
                        <span>Account</span>
                    </a>

                    <a
                        href="{{ route('waterhole.preferences.profile') }}"
                        class="nav-link @if (request()->routeIs('waterhole.preferences.profile')) is-active @endif"
                    >
                        <x-waterhole::icon icon="heroicon-o-user-circle"/>
                        <span>Profile</span>
                    </a>

                    <a
                        href="{{ route('waterhole.preferences.notifications') }}"
                        class="nav-link @if (request()->routeIs('waterhole.preferences.notifications')) is-active @endif"
                    >
                        <x-waterhole::icon icon="heroicon-o-bell"/>
                        <span>Notifications</span>
                    </a>
                @endif
            </nav>
        </div>

        <div>
            <h2 class="visually-hidden">{{ $title }}</h2>

            {{ $slot }}
        </div>
    </div>
</x-waterhole::layout>
