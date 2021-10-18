@auth
    @php
        $actionable = Waterhole\Extend\Actionable::getActionable($for);
    @endphp

    @if ($for && $actionable)
        <form action="{{ route('waterhole.action.store') }}" method="POST">
            @csrf
            <input type="hidden" name="actionable" value="{{ $actionable }}">
            <input type="hidden" name="id[]" value="{{ $for->id }}">

            @isset($return)
                <input type="hidden" name="return" value="{{ $return }}">
            @endisset

            @isset($action)
                <input type="hidden" name="action_class" value="{{ $action }}">
            @endisset

            {{ $slot }}
        </form>
    @endif
@endauth
