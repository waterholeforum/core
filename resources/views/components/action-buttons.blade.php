<div {{ $attributes->class('row') }}>
    @php
        $menu = $limit !== null && $actions->count() > $limit;
        $buttons = $menu ? $actions->take($limit ? $limit - 1 : 0) : $actions;
    @endphp

    @foreach ($buttons as $action)
        {{
            $action
                ->withRenderType(Waterhole\Actions\Action::TYPE_ICON)
                ->render(collect([$for]), [
                    'class' => 'btn btn--transparent btn--icon',
                    'form' => 'action-form',
                    'formaction' => route('waterhole.actions.store', [
                        'actionable' => $actionable,
                        'id' => $for->getKey(),
                        'return' => request()->fullUrl(),
                        'context' => $context,
                    ]),
                ])
        }}
    @endforeach

    @if ($menu)
        <x-waterhole::action-menu
            :$for
            :$context
            :button-attributes="['class' => 'btn btn--transparent btn--icon']"
            placement="bottom-end"
        />
    @endif
</div>
