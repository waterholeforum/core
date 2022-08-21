<turbo-frame
    id="post-feed"
    class="stack gap-md"
    target="_top"
    data-controller="post-feed"
    data-post-feed-filter-value="{{ $feed->currentFilter()->handle() }}"
    data-post-feed-channels-value="@json($channel ? [$channel->id] : Waterhole\Models\Channel::pluck('id'))"
>
    @components(Waterhole\Extend\PostFeedHeader::build(), compact('feed', 'channel'))

    @php
        $posts = $feed->items()->withQueryString();
    @endphp

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

    @if ($posts->isNotEmpty())
        <x-waterhole::infinite-scroll :paginator="$posts">
            @foreach ($posts as $post)
                @if ($showLastVisit && $post->last_activity_at < session('previously_seen_at'))
                    @once
                        @if (!$loop->first)
                            <div class="divider feed__last-visit-divider">New Activity</div>
                        @endif
                    @endonce
                @endif

                <x-dynamic-component
                    :component="'waterhole::post-'.$feed->currentLayout().'-item'"
                    :post="$post"
                />
            @endforeach
        </x-waterhole::infinite-scroll>
    @else
        <div class="placeholder">
            <x-waterhole::icon icon="heroicon-o-chat-alt-2" class="placeholder__visual"/>
            <p class="h4">No Posts</p>
        </div>
    @endif
</turbo-frame>
