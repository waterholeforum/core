@props(['for', 'only' => null, 'except' => null])

@php
    $actionable = Waterhole\Extend\Actionable::getActionable($for);
    $actions = collect(Waterhole\Extend\Action::for([$for]))
        ->filter(fn($action) => ! $action->hidden);

    if (isset($only)) {
        $actions = $actions->filter(fn($action) => in_array(get_class($action), $only));
    }

    if (isset($except)) {
        $actions = $actions->filter(fn($action) => ! in_array(get_class($action), $except));
    }
@endphp

@if (count($actions))
    {{ $before ?? '' }}

    <form action="{{ route('waterhole.action.store') }}" method="POST">
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}">
        <input type="hidden" name="id[]" value="{{ $for->id }}">

        @foreach ($actions as $action)
            {{ $action->render(collect([$for]), $attributes) }}
        @endforeach
    </form>

    {{ $after ?? '' }}
@endif
