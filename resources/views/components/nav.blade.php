<nav aria-labelledby="nav-title">
    <h2 id="nav-title" class="visually-hidden">Forum Navigation</h2>

    @php $channels = Waterhole\Models\Channel::withCount('unreadPosts')->get() @endphp
    @foreach (Waterhole\Models\Config::get('structure', []) as $group)
        @if (! empty($group['title']))
            <h3 class="nav-heading">{{ $group['title'] }}</h3>
        @endif
        <ul class="nav">
            @foreach ($group['children'] as $child)
                @continue(! isset($child['type']))

                @switch($child['type'] ?? null)
                    @case('channel')
                        @if ($channel = $channels->find($child['id']))
                            <li>
                                <a href="{{ $channel->url }}" class="nav-link {{ request()->fullUrlIs($channel->url.'*') ? 'is-active' : '' }}">
                                    <span class="icon">{{ emojify($channel->icon) }}</span>
                                    <span class="label">{{ $channel->name }}</span>
                                    @if ($channel->unread_posts_count)
                                        <span class="badge">{{ $channel->unread_posts_count }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        @break

                    @case('link')
                        @if (! empty($child['url']) && ! empty($child['label']))
                            <li>
                                <a href="{{ $child['url'] }}" class="nav-link {{ request()->is($child['url'].'*') ? 'is-active' : '' }}">
                                    <span class="icon">{{ emojify($child['icon']) ?? '' }}</span>
                                    <span class="label">{{ $child['label'] }}</span>
                                </a>
                            </li>
                        @endif
                        @break

                    @case('home')
                        <li>
                            <a href="{{ route('waterhole.home') }}" class="nav-link {{ request()->routeIs('waterhole.home') ? 'is-active' : '' }}" data-type="home">
                                <span class="icon">{{ emojify('🏠') }}</span>
                                <span class="label">{{ $child['label'] ?? __('waterhole::forum.home') }}</span>
                            </a>
                        </li>
                        @break
                @endswitch
            @endforeach
        </ul>
    @endforeach
</nav>
