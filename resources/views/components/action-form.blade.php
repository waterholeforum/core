@php
    $actionable = $for ? get_class($for) : null;
    $actions = resolve(\Waterhole\Extend\Core\Actions::class);
@endphp

@if ($for && $actionable && $actions->hasList($actionable))
    <form action="{{ route('waterhole.actions.store') }}" method="POST" {{ $attributes }}>
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}"/>
        <input type="hidden" name="id[]" value="{{ $for->id }}"/>

        @isset($return)
            <input type="hidden" name="return" value="{{ $return }}"/>
        @endisset

        @isset($action)
            <input type="hidden" name="action_class" value="{{ $action }}"/>
        @endisset

        {{ $slot }}
    </form>
@endif
