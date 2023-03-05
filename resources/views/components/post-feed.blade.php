<turbo-frame
    id="post-feed"
    class="post-feed stack gap-lg"
    target="_top"
    data-controller="post-feed"
    data-post-feed-filter-value="{{ $feed->currentFilter()->handle() }}"
    data-post-feed-public-channels-value="@json(Waterhole\Models\Channel::allPermitted(null))"
    data-post-feed-channels-value="@json($channel ? [$channel->id] : Waterhole\Models\Channel::pluck('id'))"
>
    @components(Waterhole\Extend\PostFeedHeader::build(), compact('feed', 'channel'))

    <div>
        <form
            class="post-feed__refresh animate-appear"
            data-post-feed-target="newActivity"
            data-turbo-frame="post-feed"
            hidden
        >
            <div>
                <button
                    type="submit"
                    class="btn btn--sm bg-activity"
                    data-action="post-feed#scrollToTop"
                >
                    <x-waterhole::icon icon="tabler-refresh"/>
                    <span>{{ __('waterhole::forum.post-feed-new-activity-button') }}</span>
                </button>
            </div>
        </form>

        @php
            $posts = $feed->items()->withQueryString();
        @endphp

        @if ($posts->isNotEmpty())
            <x-waterhole::infinite-scroll :paginator="$posts">
                <div class="post-feed__content post-{{ $feed->currentLayout() }}">
                    @foreach ($posts as $post)
                        @if ($showLastVisit && $post->last_activity_at < session('previously_seen_at'))
                            @once
                                @if (!$loop->first)
                                    <div class="divider color-accent feed__last-visit-divider">
                                        {{ __('waterhole::forum.post-feed-new-activity-heading') }}
                                    </div>
                                @endif
                            @endonce
                        @endif

                        <x-dynamic-component
                            :component="'waterhole::post-'.$feed->currentLayout().'-item'"
                            :post="$post"
                        />
                    @endforeach
                </div>
            </x-waterhole::infinite-scroll>
        @else
            <div class="placeholder">
                <x-waterhole::icon
                    icon="tabler-messages"
                    class="placeholder__icon"
                />
                <p class="h4">{{ __('waterhole::forum.feed-empty-message') }}</p>
            </div>
        @endif
    </div>
</turbo-frame>
