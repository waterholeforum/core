@php
    use Illuminate\Contracts\Pagination\CursorPaginator;

    $isCursor = $paginator instanceof CursorPaginator;
    $current = $isCursor ? $paginator->cursor()?->encode() ?? "1" : $paginator->currentPage();
    $direction = request()->query("direction");
@endphp

<turbo-frame
    id="page_{{ $current }}_frame"
    target="_top"
    data-page-frame
    @if (! $isCursor) data-page="{{ $paginator->currentPage() }}" @endif
    {{ $attributes }}
>
    @if (! $paginator->onFirstPage() && $direction !== "forwards")
        <turbo-frame
            id="page_{{ $isCursor ? $paginator->previousCursor()->encode() ?? "1" : $paginator->currentPage() - 1 }}_frame"
            src="{{ $paginator->appends("direction", "backwards")->previousPageUrl() }}"
            loading="lazy"
            class="page-placeholder page-placeholder--previous busy-spinner"
            target="_top"
            data-controller="load-backwards"
            data-page-frame
            data-page-direction="backwards"
            @if (! $isCursor) data-page="{{ $paginator->currentPage() - 1 }}" @endif
        ></turbo-frame>
    @endif

    <div id="page_{{ $current }}" tabindex="-1"></div>

    @if (! $isCursor && ! $paginator->onFirstPage() && $divider)
        <div class="divider">
            {{ __("waterhole::system.page-number-heading", ["number" => $paginator->currentPage()]) }}
        </div>
    @endif

    {{ $slot ?? "" }}

    @if ($paginator->hasMorePages() && $direction !== "backwards")
        <turbo-frame
            id="page_{{ $isCursor ? $paginator->nextCursor()->encode() : $paginator->currentPage() + 1 }}_frame"
            target="_top"
            class="page-placeholder page-placeholder--next busy-spinner"
            data-page-frame
            data-page-direction="forwards"
            @if (! $isCursor) data-page="{{ $paginator->currentPage() + 1 }}" @endif
            @if ($paginator->onFirstPage() || $endless)
                src="{{ $paginator->appends("direction", "forwards")->nextPageUrl() }}"
                loading="lazy"
            @endif
        >
            <div class="text-center p-md">
                <a
                    href="{{ $paginator->appends("direction", "forwards")->nextPageUrl() }}"
                    class="btn"
                    data-turbo-frame="_self"
                    data-page-load-more
                >
                    {{ __("waterhole::system.load-more-button") }}
                </a>
            </div>
        </turbo-frame>
    @endif
</turbo-frame>

@php
    $paginator->appends("direction", null);
@endphp
