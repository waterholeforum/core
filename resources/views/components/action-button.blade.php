@props(['for', 'action', 'return' => null])

@php
    $actionable = Waterhole\Extend\Actionable::getActionable($for);
    $action = collect(Waterhole\Extend\Action::for([$for]))
        ->filter(fn($a) => $a instanceof $action)
        ->first();
@endphp

@if ($action)
    <form action="{{ route('waterhole.action') }}" method="POST">
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}">
        <input type="hidden" name="id[]" value="{{ $for->id }}">

        @isset($return)
            <input type="hidden" name="return" value="{{ $return }}">
        @endisset

        {{ $action->render(collect([$for]), $attributes) }}
    </form>
@endif
