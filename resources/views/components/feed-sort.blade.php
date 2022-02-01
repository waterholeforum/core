<div class="tabs">
    @foreach ($feed->filters() as $filter)
        <a
            href="{{ request()->fullUrlWithQuery(['filter' => $filter->handle(), 'cursor' => null]) }}"
            class="tab"
            @if ($feed->currentFilter() === $filter) aria-current="page" @endif
        >{{ $filter->label() }}</a>
    @endforeach
</div>
