{{--@if ($paginator->hasPages())--}}
{{--    <nav class="pagination tabs">--}}
        {{-- First Page Link --}}
{{--        @if ($paginator->onFirstPage())--}}
{{--            <span class="tab" aria-disabled="true">@lang('pagination.first')</span>--}}
{{--        @else--}}
{{--            <a class="tab" href="{{ $paginator->url(1) }}">@lang('pagination.first')</a>--}}
{{--        @endif--}}

        {{-- Previous Page Link --}}
{{--        @if ($paginator->onFirstPage())--}}
{{--            <span class="tab" aria-disabled="true">@lang('pagination.previous')</span>--}}
{{--        @else--}}
{{--            <a class="tab" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>--}}
{{--        @endif--}}

        {{-- Pagination Elements --}}
{{--        @foreach ($elements as $element)--}}
{{--            --}}{{-- "Three Dots" Separator --}}
{{--            @if (is_string($element))--}}
{{--                <span class="tab" aria-disabled="true">{{ $element }}</span>--}}
{{--            @endif--}}

{{--            --}}{{-- Array Of Links --}}
{{--            @if (is_array($element))--}}
{{--                @foreach ($element as $page => $url)--}}
{{--                    <a class="tab" href="{{ $url }}" @if ($page == $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--        @endforeach--}}

        {{-- Next Page Link --}}
{{--        @if ($paginator->hasMorePages())--}}
{{--            <a class="tab" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>--}}
{{--        @else--}}
{{--            <span class="tab" aria-disabled="true" aria-label="">@lang('pagination.next')</span>--}}
{{--        @endif--}}

        {{-- Last Page Link --}}
{{--        @if ($paginator->onLastPage())--}}
{{--            <span class="tab" aria-disabled="true">@lang('pagination.last')</span>--}}
{{--        @else--}}
{{--            <a class="tab" href="{{ $paginator->fragment('bottom')->url($paginator->lastPage()) }}">@lang('pagination.last')</a>--}}
{{--        @endif--}}
{{--    </nav>--}}
{{--@endif--}}
