@props(['feed', 'channel' => null])

@php
    $posts = $feed->posts()->withQueryString();
@endphp

@if ($posts->isNotEmpty())
    <turbo-frame id="page_{{ $posts->cursor()?->encode() ?? '1' }}" target="_top">
        <div class="post-{{ $feed->currentLayout() }}">
            @foreach ($posts as $post)
                @if ($showLastVisit && $post->last_activity_at < session('previously_seen_at'))
                    @once
                        @if (! $loop->first)
                            <div class="divider feed__last-visit-divider">Last Visit</div>
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
            >
                <div class="loading-indicator"></div>
            </turbo-frame>
        @endif
    </turbo-frame>

    <noscript>
        {{ $posts->links() }}
    </noscript>

    <br><br><br>
@else
    <div class="placeholder">
        <x-waterhole::icon icon="heroicon-o-chat-alt-2" class="placeholder__visual"/>
        <h3>No Posts</h3>
    </div>
@endif
