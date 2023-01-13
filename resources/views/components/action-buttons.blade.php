<form action="{{ route('waterhole.action.store') }}" method="POST" {{ $attributes }}>
    @csrf
    <input type="hidden" name="actionable" value="{{ $actionable }}">
    <input type="hidden" name="id[]" value="{{ $for->getKey() }}">

    {{ $before ?? '' }}

    @foreach ($actions as $action)
        {{ $action->render(collect([$for]), $buttonAttributes, $icons) }}
    @endforeach

    {{ $after ?? '' }}
</form>
