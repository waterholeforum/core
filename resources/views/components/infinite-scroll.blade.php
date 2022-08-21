@php
    use Illuminate\Contracts\Pagination\CursorPaginator;

    $isCursor = $paginator instanceof CursorPaginator;
    $current = $isCursor ? $paginator->cursor()?->encode() ?? '1' : $paginator->currentPage();
@endphp

<turbo-frame id="page_{{ $current }}_frame" target="_top" {{ $attributes }}>
    @if (!$paginator->onFirstPage() && request()->query('direction') !== 'forwards')
        <turbo-frame
            id="page_{{ $isCursor ? $paginator->previousCursor()->encode() ?? '1' : $paginator->currentPage() - 1 }}_frame"
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
        id="page_{{ $current }}"
        tabindex="-1"
    >
        @if (!$isCursor && !$paginator->onFirstPage() && $divider)
            <div class="divider">
                {{ __('waterhole::system.page-number-heading', ['number' => $paginator->currentPage()]) }}
            </div>
        @endif

        {{ $slot ?? '' }}
    </div>

    @if ($paginator->hasMorePages() && request('direction') !== 'backwards')
        <turbo-frame
            id="page_{{ $isCursor ? $paginator->nextCursor()->encode() : $paginator->currentPage() + 1 }}_frame"
            class="next-page"
            @if ($paginator->onFirstPage() || $endless)
                src="{{ $paginator->appends('direction', 'forwards')->nextPageUrl() }}"
                loading="lazy"
            @endif
        >
            @if ($paginator->onFirstPage() || $endless)
                <div class="loading"></div>
            @else
                <div class="text-center" style="margin-top: var(--space-md)">
                    <a href="{{ $paginator->appends('direction', 'forwards')->nextPageUrl() }}" class="btn">
                        {{ __('waterhole::system.load-more-button') }}
                    </a>
                </div>
            @endif
        </turbo-frame>
    @endif
</turbo-frame>

@php
    $paginator->appends('direction', null);
@endphp
