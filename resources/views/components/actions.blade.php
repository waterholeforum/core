@props(['actionable', 'items'])

@if (count($items))
  <form action="{{ route('waterhole.action') }}" method="POST">
    @csrf
    <input type="hidden" name="actionable" value="{{ $actionable }}">

    @foreach ($items as $item)
      <input type="hidden" name="id[]" value="{{ $item->id }}">
    @endforeach

    @foreach (\Waterhole\Extend\Actions::for($items) as $action)
      <button name="action" value="{{ get_class($action) }}"
        @if ($action->confirm) formaction="{{ route('waterhole.action.confirm') }}" formmethod="GET" @endif
      >
        {{ $action->name() }}
      </button>
    @endforeach
  </form>
@endif
