@props(['actionable', 'items' => [], 'actions' => Waterhole\Extend\Actions::for($items)])

@if (count($items) && count($actions))
  <form action="{{ route('waterhole.action') }}" method="POST">
    @csrf
    <input type="hidden" name="actionable" value="{{ $actionable }}">

    @foreach ($items as $item)
      <input type="hidden" name="id[]" value="{{ $item->id }}">
    @endforeach

    @foreach ($actions as $action)
      @continue($action->hidden)
      {{ $action->render(collect($items)) }}
    @endforeach
  </form>
@endif
