<form action="{{ route('waterhole.action.store') }}" method="POST" {{ $attributes }}>
    @csrf
    <input type="hidden" name="actionable" value="{{ $actionable }}">
    <input type="hidden" name="id[]" value="{{ $for->getKey() }}">

    @php $menu = $limit !== null && $actions->count() > $limit @endphp

    @foreach ($menu ? $actions->take($limit ? $limit - 1 : 0) : $actions as $action)
        {{ $action->render(collect([$for]), $buttonAttributes, $tooltips) }}
    @endforeach

    @if ($menu)
        <ui-popup placement="{{ $placement }}" class="js-only row">
            @if (isset($button))
                {{ $button }}
            @else
                <button type="button" {{ new Illuminate\View\ComponentAttributeBag($buttonAttributes) }}>
                    @icon('tabler-dots')
                    <ui-tooltip>{{ __('waterhole::system.actions-button') }}</ui-tooltip>
                </button>
            @endif
            <ui-menu class="menu" hidden>
                @foreach ($actions->skip($limit ? $limit - 1 : 0) as $action)
                    {{ $action->render(collect([$for]), ['class' => 'menu-item', 'role' => 'menuitem']) }}
                @endforeach
            </ui-menu>
        </ui-popup>
    @endif
</form>
