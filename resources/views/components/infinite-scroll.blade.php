<turbo-frame id="page_{{ $paginator->currentPage() }}_frame" target="_top">
    @if (! $paginator->onFirstPage() && request()->query('direction') !== 'forwards')
        <turbo-frame
            id="page_{{ $paginator->currentPage() - 1 }}_frame"
            src="{{ $paginator->appends('direction', 'backwards')->previousPageUrl() }}"
            loading="lazy"
            class="next-page"
            target="_top"
            data-controller="load-backwards"
        >
            <div class="loading"></div>
        </turbo-frame>
    @endif

    <div
        id="page_{{ $paginator->currentPage() }}"
        tabindex="-1"
    >
        @if (! $paginator->onFirstPage() && $divider)
            <div class="divider">Page {{ $paginator->currentPage() }}</div>
        @endif

        {{ $slot ?? '' }}
    </div>

    @if ($paginator->hasMorePages())
        @if (request('direction') !== 'backwards')
            <turbo-frame
                id="page_{{ $paginator->currentPage() + 1 }}_frame"
                src="{{ $paginator->appends('direction', 'forwards')->nextPageUrl() }}"
                loading="lazy"
                class="next-page"
                target="_top"
            >
                <div class="loading"></div>
            </turbo-frame>
        @endif
    @endif
</turbo-frame>

@php
    $paginator->appends('direction', null);
@endphp
