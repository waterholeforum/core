<ui-popup placement="bottom-start" class="index-nav-structure">
    <button class="btn">
        <x-waterhole::icon :icon="$current['icon'] ?? null"/>
        <span>{{ $current['label'] ?? 'Navigation' }}</span>
        <x-heroicon-o-selector class="icon"/>
    </button>

    <ui-menu class="menu">
        <nav aria-labelledby="nav-title">
            <h2 id="nav-title" class="visually-hidden">Forum Navigation</h2>

            <ul class="nav">
                @foreach ($nav as $item)
                    @if (! empty($item['heading']))
                        <li class="nav-heading">{{ $item['heading'] }}</li>
                    @else
                        <li>
                            <a href="{{ isset($item['route']) ? route($item['route']) : $item['url'] ?? null }}" class="nav-link {{ $current === $item ? 'is-active' : '' }}">
                                <x-waterhole::icon :icon="$item['icon']"/>
                                <span class="label">{{ $item['label'] }}</span>
                                @isset ($item['badge'])
                                    <span class="badge badge--primary">{{ $item['badge'] }}</span>
                                @endisset
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </ui-menu>
</ui-popup>
