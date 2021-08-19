@props(['feed'])

<details>
  <summary>Sort by {{ $feed->currentSort()->name() }}</summary>
  @foreach ($feed->sorts() as $sort)
    <a href="{{ request()->fullUrlWithQuery(['sort' => $sort->handle()]) }}">{{ $sort->name() }}</a>
  @endforeach
</details>
