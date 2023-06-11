@if ($paginator->hasPages())
    <nav class="pagination tabs justify-center">
        {{-- Previous Page Link --}}

        @if ($paginator->onFirstPage())
            <span class="tab" aria-disabled="true">
                {{ __('waterhole::system.pagination-previous-link') }}
            </span>
        @else
            <a class="tab" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                {{ __('waterhole::system.pagination-previous-link') }}
            </a>
        @endif

        {{-- Next Page Link --}}

        @if ($paginator->hasMorePages())
            <a class="tab" href="{{ $paginator->nextPageUrl() }}" rel="next">
                {{ __('waterhole::system.pagination-next-link') }}
            </a>
        @else
            <span class="tab" aria-disabled="true">
                {{ __('waterhole::system.pagination-next-link') }}
            </span>
        @endif
    </nav>
@endif
