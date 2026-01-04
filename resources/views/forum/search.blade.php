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

        <form
            action="{{ route('waterhole.search') }}"
            class="lead row gap-xs card card__body"
            role="search"
        >
            <div class="input-container full-width">
                @icon('tabler-search', ['class' => 'no-pointer'])
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="{{ __('waterhole::forum.search-placeholder') }}"
                    aria-label="{{ __('waterhole::forum.search-placeholder') }}"
                    autofocus
                />
            </div>
            <button type="submit" class="btn bg-accent">
                {{ __('waterhole::forum.search-button') }}
            </button>
        </form>

        @isset($hits)
            @if ($hits->count())
                <div class="with-sidebar">
                    <div class="sidebar sidebar--sticky">
                        <x-waterhole::collapsible-nav
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
                                @icon('tabler-filter')
                                <span>{{ __('waterhole::forum.search-filter-button') }}</span>
                            </x-slot>
                        </x-waterhole::collapsible-nav>
                    </div>

                    <div class="stack gap-md">
                        <div class="row gap-xs wrap justify-between">
                            <h2 class="h4">
                                {{ __('waterhole::forum.search-showing-results' . ($results->exhaustiveTotal ? '' : '-non-exhaustive') . '-title', ['total' => $results->total]) }}
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

                        <div class="card search-results post-list">
                            <x-waterhole::infinite-scroll :paginator="$hits" divider>
                                @foreach ($hits as $hit)
                                    <x-waterhole::post-list-item
                                        :post="$hit->post"
                                        :excerpt="$hit->body"
                                    />
                                @endforeach
                            </x-waterhole::infinite-scroll>
                        </div>
                    </div>
                </div>
            @else
                <div class="placeholder">
                    @icon('tabler-search', ['class' => 'placeholder__icon'])

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
