<turbo-frame
    id="feed"
    class="stack-md"
    target="_top"
    data-controller="feed"
    data-feed-sort-value="{{ $feed->currentSort()->handle() }}"
    data-feed-channels-value="@json($channel ? [$channel->id] : Waterhole\Models\Channel::pluck('id'))"
>
    @components(Waterhole\Extend\FeedHeader::getComponents(), compact('feed', 'channel'))

    @php
        $posts = $feed->posts()->withQueryString();
    @endphp

    @if ($posts->isNotEmpty())
        <form
            class="feed__new-activity"
            data-feed-target="newActivity"
            data-turbo-frame="feed"
            hidden
        >
            <input type="hidden" name="update_previously_seen_at" value="1">
            <div>
                <button type="submit" class="btn btn--small" data-action="feed#scrollToTop">
                    <x-waterhole::icon icon="heroicon-s-refresh"/>
                    <span>New Activity</span>
                </button>
            </div>
        </form>

        <turbo-frame id="page_{{ $posts->cursor()?->encode() ?? '1' }}" target="_top">
            <div class="post-{{ $feed->currentLayout() }}">
                @foreach ($posts as $post)
                    @if ($showLastVisit && $post->last_activity_at < session('previously_seen_at'))
                        @once
                            @if (! $loop->first)
                                <div class="divider feed__last-visit-divider">New Activity</div>
                            @endif
                        @endonce
                    @endif

                    <x-dynamic-component
                        :component="'waterhole::post-'.$feed->currentLayout().'-item'"
                        :post="$post"
                    />
                @endforeach
            </div>

            @if ($posts->hasMorePages())
                <turbo-frame
                    id="page_{{ $posts->nextCursor()->encode() }}"
                    src="{{ $posts->nextPageUrl() }}"
                    loading="lazy"
                    class="next-page"
                    target="_top"
                    data-controller="attribute"
                    data-action="turbo:frame-load->attribute#remove"
                    data-attribute-name-param="src"
                >
                    <div class="loading-indicator"></div>
                </turbo-frame>
            @endif
        </turbo-frame>

        <noscript>
            {{ $posts->links() }}
        </noscript>
    @else
        <div class="placeholder">
            <x-waterhole::icon icon="heroicon-o-chat-alt-2" class="placeholder__visual"/>
            <h3>No Posts</h3>
        </div>
    @endif
</turbo-frame>
