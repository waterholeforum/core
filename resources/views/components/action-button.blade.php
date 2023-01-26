@php
    $actionable = Waterhole\Extend\Actionables::getActionableName($for);
    $action = collect(Waterhole\Extend\Actions::for([$for]))
        ->filter(fn($a) => $a instanceof $action)
        ->first();
@endphp

@if ($action)
    {{ $before ?? '' }}

    <form action="{{ route('waterhole.action.store') }}" method="POST">
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}">
        <input type="hidden" name="id[]" value="{{ $for->id }}">

        @isset($return)
            <input type="hidden" name="return" value="{{ $return }}">
        @endisset

        {{ $action->render(collect([$for]), $attributes->getAttributes(), $icon) }}
    </form>

    {{ $after ?? '' }}
@else
    {{ $unauthorized ?? '' }}
@endif
