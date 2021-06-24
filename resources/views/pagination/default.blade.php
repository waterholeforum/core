@if ($paginator->hasPages())
    <nav class="pagination tabs">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="tab" aria-disabled="true">{{ __('waterhole::system.pagination-previous-link') }}</span>
        @else
            <a class="tab" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('waterhole::system.pagination-previous-link') }}</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator--}}
            @if (is_string($element))
                <span class="tab" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <a class="tab" href="{{ $url }}" @if ($page == $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="tab" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('waterhole::system.pagination-next-link') }}</a>
        @else
            <span class="tab" aria-disabled="true" aria-label="">{{ __('waterhole::system.pagination-next-link') }}</span>
        @endif
@endif
