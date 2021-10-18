@php
    $channels = Waterhole\Models\Channel::query()
        //->when(Auth::user(), fn($query) => $query->withCount('unreadPosts'))
        ->get();

    $structure = Waterhole\Models\Config::get('structure', []);
    $nav = [];
    $current = null;

    foreach ($structure as $group) {
        $children = [];

        foreach ($group['children'] as $child) {
            switch ($child['type'] ?? null) {
                case 'channel':
                    if ($channel = $channels->find($child['id'])) {
                        $children[] = $item = [
                            'url' => $channel->url,
                            'icon' => $channel->icon,
                            'label' => $channel->name,
                            'badge' => $channel->unread_posts_count ?: null,
                        ];
                        if (request()->fullUrlIs($channel->url.'*')) {
                            $current = $item;
                        }
                    }
                    break;

                case 'link':
                    if (! empty($child['url']) && ! empty($child['label'])) {
                        $children[] = $item = [
                            'url' => $child['url'],
                            'icon' => $child['icon'] ?? null,
                            'label' => $child['label'],
                        ];
                        if (request()->fullUrlIs($child['url'].'*')) {
                            $current = $item;
                        }
                    }
                    break;

                case 'home':
                    $children[] = $item = [
                        'url' => route('waterhole.home'),
                        'icon' => 'heroicon-o-home',
                        'label' => $child['label'] ?? __('waterhole::forum.home'),
                    ];
                    if (request()->routeIs('waterhole.home')) {
                        $current = $item;
                    }
            }
        }

        $nav[] = [
            'title' => $group['title'] ?? null,
            'children' => $children,
        ];
    }
@endphp

<ui-popup placement="bottom-start" class="index-nav-structure">
    <button class="btn">
        <x-waterhole::icon :icon="$current['icon'] ?? null"/>
        <span>{{ $current['label'] ?? 'Navigation' }}</span>
        <x-heroicon-o-selector class="icon"/>
    </button>

    <ui-menu class="menu">
        <nav aria-labelledby="nav-title">
            <h2 id="nav-title" class="visually-hidden">Forum Navigation</h2>

            @foreach ($nav as $group)
                @if (! empty($group['title']))
                    <h3 class="nav-heading">{{ $group['title'] }}</h3>
                @endif
                <ul class="nav">
                    @foreach ($group['children'] as $child)
                        <li>
                            <a href="{{ $child['url'] }}" class="nav-link {{ $current === $child ? 'is-active' : '' }}">
                                <x-waterhole::icon :icon="$child['icon']"/>
                                <span class="label">{{ $child['label'] }}</span>
                                @isset ($child['badge'])
                                    <span class="badge">{{ $child['badge'] }}</span>
                                @endisset
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </nav>
    </ui-menu>
</ui-popup>
