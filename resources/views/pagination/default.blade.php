@if ($paginator->hasPages())
    <nav class="pagination tabs">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="tab" aria-disabled="true">@lang('pagination.previous')</span>
        @else
            <a class="tab" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="tab" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="tab" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="tab" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="tab" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
        @else
            <span class="tab" aria-disabled="true" aria-label="">@lang('pagination.next')</span>
        @endif
    </nav>
@endif
