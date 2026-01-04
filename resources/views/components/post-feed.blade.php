<turbo-frame
    id="post-feed"
    class="post-feed stack gap-lg"
    target="_top"
    data-controller="post-feed"
    data-post-feed-filter-value="{{ $feed->currentFilter->handle() }}"
    data-post-feed-public-channels-value="@json($publicChannels)"
    data-post-feed-channels-value="@json($channels)"
>
    @components(resolve(\Waterhole\Extend\Ui\PostFeed::class)->header, compact('feed', 'channel'))

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
                    @icon('tabler-refresh')
                    <span>{{ __('waterhole::forum.post-feed-new-activity-button') }}</span>
                </button>
            </div>
        </form>

        @if ($posts->isNotEmpty())
            <div class="post-feed__content {{ $feed->layout->wrapperClass() }}">
                <x-waterhole::infinite-scroll :paginator="$posts">
                    @foreach ($posts as $post)
                        @if ($showLastVisit && $post->last_activity_at < session('previously_seen_at'))
                            @once
                                @if (! $loop->first)
                                    <div class="divider color-accent feed__last-visit-divider">
                                        {{ __('waterhole::forum.post-feed-new-activity-heading') }}
                                    </div>
                                @endif
                            @endonce
                        @endif

                        <x-dynamic-component
                            :component="$feed->layout->itemComponent()"
                            :post="$post"
                        />
                    @endforeach
                </x-waterhole::infinite-scroll>
            </div>
        @else
            <div class="placeholder">
                @icon('tabler-messages', ['class' => 'placeholder__icon'])
                <p class="h4">{{ __('waterhole::forum.post-feed-empty-message') }}</p>
            </div>
        @endif
    </div>
</turbo-frame>
