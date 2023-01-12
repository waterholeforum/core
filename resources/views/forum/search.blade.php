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
                    icon="tabler-search"
                    class="no-pointer"
                />
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="{{ __('waterhole::forum.search-placeholder') }}"
                    autofocus
                >
            </div>
            <button type="submit" class="btn bg-accent">
                {{ __('waterhole::forum.search-button') }}
            </button>
        </form>

        @isset($hits)
            @if ($hits->count())
                <div class="with-sidebar">
                    <div class="sidebar sidebar--sticky">
                        <x-waterhole::responsive-nav
                            :components="$channels
                                ->map(fn($channel) => new Waterhole\View\Components\NavLink(
                                    label: $channel->name,
                                    icon: $channel->icon,
                                    badge: $results->channelHits[$channel->id] ?? null,
                                    href: $selectedChannels->contains($channel) ? request()->fullUrlWithoutQuery(['channels', 'page']) : request()->fullUrlWithQuery(['channels' => $channel->id, 'page' => null]),
                                    active: $selectedChannels->contains($channel)
                                ))
                                ->all()"
                        >
                            <x-slot name="empty">
                                <x-waterhole::icon icon="tabler-filter"/>
                                <span>{{ __('waterhole::forum.search-filter-button') }}</span>
                            </x-slot>
                        </x-waterhole::responsive-nav>
                    </div>

                    <div class="stack gap-md">
                        <div class="row gap-xs wrap justify-between">
                            <h2 class="h4">
                                {{ __('waterhole::forum.search-showing-results'.($results->exhaustiveTotal ? '' : '-non-exhaustive').'-title', ['total' => $results->total]) }}
                            </h2>

                            <x-waterhole::selector
                                placement="bottom-end"
                                button-class="btn btn--sm btn--transparent color-accent"
                                :value="$currentSort"
                                :options="$sorts"
                                :label='fn($sort) => __("waterhole::forum.search-sort-$sort")'
                                :href="fn($sort) => request()->fullUrlWithQuery(['sort' => $sort, 'page' => null])"
                            />
                        </div>

                        <x-waterhole::infinite-scroll :paginator="$hits" divider>
                            <ul role="list" class="search-results stack gap-lg">
                                @foreach ($hits as $hit)
                                    <li class="stack gap-xs">
                                        <x-waterhole::post-list-item :post="$hit->post"/>
                                        <div class="post-summary">
                                            <div class="post-summary__avatar"></div>
                                            <div class="content text-xs">
                                                {{ $hit->body }}
                                            </div>
                                        </div>

{{--                                        <div class="post-summary row gap-md align-start">--}}
{{--                                            <x-waterhole::avatar--}}
{{--                                                :user="$hit->post->user"--}}
{{--                                                class="post-summary__avatar no-shrink"--}}
{{--                                            />--}}

{{--                                            <div class="post-summary__content grow stack gap-xxs">--}}
{{--                                                <h3 class="post-summary__title h4 weight-normal">--}}
{{--                                                    <a href="{{ $hit->post->url }}">{{ $hit->title }}</a>--}}
{{--                                                </h3>--}}

{{--                                                <div class="post-summary__info row wrap gap-y-xxs gap-x-sm text-xxs color-muted">--}}
{{--                                                    @components(Waterhole\Extend\PostInfo::build(), ['post' => $hit->post])--}}
{{--                                                </div>--}}


{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </li>
                                @endforeach
                            </ul>
                        </x-waterhole::infinite-scroll>
                    </div>
                </div>
            @else
                <div class="placeholder">
                    <x-waterhole::icon
                        class="placeholder__icon"
                        icon="tabler-search"
                    />

                    <p class="h4">
                        {{ __('waterhole::forum.search-empty-message') }}
                    </p>

                    @if ($results->error)
                        <p>{{ $results->error }}</p>
                    @endif
                </div>
            @endif
        @endisset
    </div>
</x-waterhole::layout>
