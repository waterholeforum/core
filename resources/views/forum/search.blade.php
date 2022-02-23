@php
    $title = __('waterhole::forum.search-results-title', ['query' => request('q')]);
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section stack gap-xl">
        @if (request('q'))
            <h1 hidden data-page-target="title">
                <span>{{ $title }}</span>
            </h1>
        @endif

        <form action="{{ route('waterhole.search') }}" class="lead row gap-xs">
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
            <button type="submit" class="btn btn--primary">
                {{ __('waterhole::forum.search-button') }}
            </button>
        </form>

        @isset($hits)
            @if ($hits->count())
                <div class="with-sidebar">
                    <div class="sidebar sidebar--sticky nav">
                        @foreach ($channels as $channel)
                            <a
                                href="{{ $selectedChannels->contains($channel) ? request()->fullUrlWithoutQuery(['channels', 'page']) : request()->fullUrlWithQuery(['channels' => $channel->id, 'page' => null]) }}"
                                class="nav-link"
                                @if ($selectedChannels->contains($channel)) aria-current="page" @endif
                            >
                                <x-waterhole::icon :icon="$channel->icon"/>
                                <span>{{ $channel->name }}</span>
                                @if ($selectedChannels->contains($channel))
                                    <x-waterhole::icon icon="heroicon-s-x" class="push-end text-sm"/>
                                @elseif (isset($results->channelHits[$channel->id]))
                                    <span class="badge">{{ $results->channelHits[$channel->id] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <div class="stack gap-md">
                        <div class="row gap-xs wrap justify-between">
                            <h2 class="h3">
                                {{ __('waterhole::forum.search-showing-results'.($results->exhaustiveTotal ? '' : '-non-exhaustive').'-title', ['total' => $results->total]) }}
                            </h2>

                            <x-waterhole::selector
                                placement="bottom-end"
                                button-class="btn btn--small btn--link"
                                :value="$currentSort"
                                :options="$sorts"
                                :label='fn($sort) => __("waterhole::forum.search-sort-$sort")'
                                :href="fn($sort) => request()->fullUrlWithQuery(['sort' => $sort, 'page' => null])"
                            />
                        </div>

                        <turbo-frame id="page_{{ $hits->currentPage() }}" target="_top">
                            <ul role="list" class="post-list search-results">
                                @foreach ($hits as $hit)
                                    <li>
                                        <div class="post-list-item">
                                            <div class="post-summary">
                                                <x-waterhole::avatar
                                                    :user="$hit->post->user"
                                                    class="post-summary__avatar"
                                                />

                                                <div class="post-summary__content stack gap-xs">
                                                    <h3 class="post-summary__title">
                                                        <a href="{{ $hit->post->url }}">{{ $hit->title }}</a>
                                                    </h3>

                                                    <div class="post-summary__info">
                                                        @components(Waterhole\Extend\PostInfo::build(), ['post' => $hit->post])
                                                    </div>

                                                    <div class="content color-muted text-xs">
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
                    <x-waterhole::icon
                        class="placeholder__visual"
                        icon="heroicon-o-search"
                    />

                    <h2 class="h3">
                        {{ __('waterhole::forum.search-empty-message') }}
                    </h2>

                    @if ($results->error)
                        <p>{{ $results->error }}</p>
                    @endif
                </div>
            @endif
        @endisset
    </div>
</x-waterhole::layout>
