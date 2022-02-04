<x-waterhole::layout>
    <div class="container section">
        @if (request('q'))
            <h1 hidden data-page-target="title">
                <span>Search Results for <em>{{ request('q') }}</em></span>
            </h1>
        @endif

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

        @isset($hits)
            <br><br>

            @if ($hits->count())
                <div class="with-sidebar-start">
                    <div class="nav sidebar sidebar--sticky">
                        @foreach ($channels as $channel)
                            <a
                                href="{{ $selectedChannels->contains($channel) ? request()->fullUrlWithoutQuery(['channels', 'page']) : request()->fullUrlWithQuery(['channels' => $channel->id, 'page' => null]) }}"
                                class="nav-link"
                                @if ($selectedChannels->contains($channel)) aria-current="page" @endif
                            >
                                <x-waterhole::icon :icon="$channel->icon"/>
                                <span>{{ $channel->name }}</span>
                                @if ($selectedChannels->contains($channel))
                                    <x-waterhole::icon icon="heroicon-s-x" style="margin-left: auto; font-size: inherit"/>
                                @elseif (isset($results->channelHits[$channel->id]))
                                    <span class="badge">{{ $results->channelHits[$channel->id] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <div class="stack-md">
                        <div class="toolbar">
                            <h2 class="h3">Showing {{ number_format($results->total) }}{{ $results->exhaustiveTotal ? '' : '+' }} results</h2>
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
                                                        @components(Waterhole\Extend\PostInfo::build(), ['post' => $hit->post])
                                                    </div>
                                                    <div class="content" style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: .5em">
                                                        {{ $hit->body }}
                                                    </div>
                                                </div>
                                            </div>
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
                    @if ($results->error)
                        <p>{{ $results->error }}</p>
                    @endif
                </div>
            @endif
        @endisset
    </div>
</x-waterhole::layout>
