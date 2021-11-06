<x-waterhole::layout>
    <div class="container section">
        <h1 hidden data-page-target="title">
            <span>Search Results for <em>{{ request('q') }}</em></span>
        </h1>

        <form action="{{ route('waterhole.search') }}" class="lead toolbar toolbar--nowrap">
            <div class="input-container full-width">
                <x-waterhole::icon
                    icon="heroicon-o-search"
                    class="pointer-events-none"
                />
                <input
                    class="input"
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search"
                >
            </div>
            <button type="submit" class="btn btn--primary">Search</button>
        </form>

        <br><br>

        @if ($hits->count())
            <div class="with-sidebar-start">
                <div class="nav sidebar--sticky">
                    @foreach ($channels as $channel)
                        <a
                            href="{{ request('channel') == $channel->id ? request()->fullUrlWithoutQuery(['channel', 'page']) : request()->fullUrlWithQuery(['channel' => $channel->id, 'page' => null]) }}"
                            class="nav-link"
                            @if (request('channel') == $channel->id) aria-current="page" @endif
                        >
                            <x-waterhole::icon :icon="$channel->icon"/>
                            <span>{{ $channel->name }}</span>
                            @if (request('channel') == $channel->id)
                                <x-waterhole::icon icon="heroicon-s-x" style="margin-left: auto; font-size: inherit"/>
                            @elseif (isset($channelHits[$channel->id]))
                                <span class="badge">{{ $channelHits[$channel->id] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="stack-md">
                    <div class="toolbar">
                        <h2 class="h3">Showing {{ number_format($total) }}{{ $exhaustiveTotal ? '' : '+' }} results</h2>
                        <div class="spacer"></div>
                        <ui-popup placement="bottom-end">
                            <button type="button" class="btn btn--small btn--link">
                                <span>Sort by {{ ucfirst($currentSort) }}</span>
                                <x-waterhole::icon icon="heroicon-s-chevron-down"/>
                            </button>
                            <ui-menu class="menu" hidden>
                                @foreach ($sorts as $sort)
                                    <a
                                        href="{{ request()->fullUrlWithQuery(['sort' => $sort, 'page' => null]) }}"
                                        role="menuitem"
                                        class="menu-item"
                                        @if ($currentSort === $sort) aria-current="page" @endif
                                    >
                                        <span>{{ ucfirst($sort) }}</span>
                                        @if ($currentSort === $sort)
                                            <x-waterhole::icon icon="heroicon-s-check" class="menu-item-check"/>
                                        @endif
                                    </a>
                                @endforeach
                            </ui-menu>
                        </ui-popup>
                    </div>

                    <turbo-frame id="page_{{ $hits->currentPage() }}" target="_top">
                        <ul role="list" class="post-list search-results">
                            @foreach ($hits as $i => $hit)
                                <li data-index="{{ $i }}">
                                    <div class="post-list-item">
                                        <div class="post-summary">
                                            <x-waterhole::avatar
                                                :user="$hit->post->user"
                                                class="post-summary__avatar"
                                            />
                                            <div class="post-summary__content">
                                                <h3 class="post-summary__title">
                                                    <a href="{{ $hit->post->url }}">{{ $hit->title }}</a>
                                                </h3>
                                                <div class="post-summary__info">
                                                    @components(Waterhole\Extend\PostInfo::getComponents(), ['post' => $hit->post])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-left: 4.1em; color: var(--color-text-muted); font-size: var(--text-xs); margin-top: .5em">
                                        {{ $hit->body }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if ($hits->hasMorePages())
                            <turbo-frame
                                id="page_{{ $hits->currentPage() + 1 }}"
                                src="{{ $hits->nextPageUrl() }}"
                                loading="lazy"
                                class="next-page"
                                target="_top"
                            >
                                <div class="loading-indicator"></div>
                            </turbo-frame>
                        @endif
                    </turbo-frame>

                    <noscript>
                        {{ $hits->links() }}
                    </noscript>
                </div>
            </div>
        @else
            <div class="placeholder">
                <x-waterhole::icon icon="heroicon-o-search" class="placeholder__visual"/>
                <h2 class="h3">No Results Found</h2>
            </div>
        @endif
    </div>
</x-waterhole::layout>
