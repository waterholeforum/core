<div {{ $attributes }}>
    @if ($items->isNotEmpty())
        <turbo-frame id="page_{{ $items->cursor()?->encode() ?? '1' }}" target="_top">
            {{ $slot ?? '' }}

            @if ($items->hasMorePages())
                <turbo-frame
                    id="page_{{ $items->nextCursor()->encode() }}"
                    src="{{ $items->nextPageUrl() }}"
                    loading="lazy"
                    class="next-page"
                    target="_top"
                >
                    <div class="loading"></div>
                </turbo-frame>
            @endif
        </turbo-frame>

        <noscript>
            {{ $items->links() }}
        </noscript>
    @else
        @isset($empty)
            {{ $empty }}
        @else
            <div class="placeholder">
                <x-waterhole::icon icon="heroicon-o-chat-alt-2" class="placeholder__visual"/>
                <h3>No Items</h3>
            </div>
        @endif
    @endif
</div>
