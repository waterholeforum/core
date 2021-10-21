@props(['feed'])

<div class="tabs">
    @foreach ($feed->sorts() as $sort)
        <a
            href="{{ request()->fullUrlWithQuery(['sort' => $sort->handle(), 'cursor' => null]) }}"
            class="tab"
            title="{{ $sort->description() }}"
            @if ($feed->currentSort() === $sort) aria-current="page" @endif
        >{{ $sort->name() }}</a>
    @endforeach
</div>
