<div {{ $attributes->class('row') }}>
    <form action="{{ route('waterhole.actions.store') }}" method="POST" class="row">
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}" />
        <input type="hidden" name="id[]" value="{{ $for->getKey() }}" />

        @php
            $menu = $limit !== null && $actions->count() > $limit;
        @endphp

        @foreach ($menu ? $actions->take($limit ? $limit - 1 : 0) : $actions as $action)
            {{
                $action
                    ->withRenderType(Waterhole\Actions\Action::TYPE_ICON)
                    ->render(collect([$for]), ['class' => 'btn btn--transparent btn--icon'])
            }}
        @endforeach
    </form>

    @if ($menu)
        <x-waterhole::action-menu
            :$for
            :$context
            :button-attributes="['class' => 'btn btn--transparent btn--icon']"
            placement="bottom-end"
        />
    @endif
</div>
