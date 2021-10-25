@if ($paginator->hasPages())
    <nav class="pagination tabs justify-center">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="tab" aria-disabled="true">@lang('pagination.previous')</span>
        @else
            <a class="tab" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="tab" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
        @else
            <span class="tab" aria-disabled="true">@lang('pagination.next')</span>
        @endif
    </nav>
@endif
