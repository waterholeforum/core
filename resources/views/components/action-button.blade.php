@if ($actionInstance)
    {{ $before ?? '' }}

    <form
        action="{{ route('waterhole.actions.store') }}"
        method="POST"
        {{ new Illuminate\View\ComponentAttributeBag($formAttributes) }}
    >
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}">
        <input type="hidden" name="id[]" value="{{ $for->id }}">

        @isset ($return)
            <input type="hidden" name="return" value="{{ $return }}">
        @endisset

        {{ $actionInstance->render(collect([$for]), $attributes->getAttributes(), $icon) }}
    </form>

    {{ $after ?? '' }}
@else
    {{ $unauthorized ?? '' }}
@endif
