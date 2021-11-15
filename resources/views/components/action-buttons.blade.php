@php
    $actionable = Waterhole\Extend\Actionable::getActionable($for);
    $actions = collect(Waterhole\Extend\Action::for([$for]));

    if (isset($only)) {
        $actions = $actions->filter(fn($action) => in_array(get_class($action), $only));
    }

    if (isset($exclude)) {
        $actions = $actions->filter(fn($action) => ! in_array(get_class($action), $exclude));
    }

    $actions = $actions
        ->filter(fn($action) => ! $action instanceof Waterhole\Actions\Action || $action->visible(collect([$for]), $context))->values()
        ->reject(fn($action, $i) => $action instanceof Waterhole\Views\Components\MenuDivider && $i === 0);
@endphp

@if (count($actions))
    <form action="{{ route('waterhole.action.store') }}" method="POST" {{ $attributes }}>
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}">
        <input type="hidden" name="id[]" value="{{ $for->id }}">

        {{ $before ?? '' }}

        @foreach ($actions as $action)
            {{ $action->render(collect([$for]), new Illuminate\View\ComponentAttributeBag($buttonAttributes)) }}
        @endforeach

        {{ $after ?? '' }}
    </form>
@endif
