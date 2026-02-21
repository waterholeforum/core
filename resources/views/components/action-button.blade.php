@if ($actionInstance)
    {{ $before ?? '' }}

    {{
        $actionInstance->withRenderType($icon ? Waterhole\Actions\Action::TYPE_ICON : Waterhole\Actions\Action::TYPE_BUTTON)->render(
            collect([$for]),
            $attributes
                ->merge(
                    [
                        'form' => 'action-form',
                        'formaction' => route('waterhole.actions.store', [
                            'actionable' => $actionable,
                            'id' => $for->getKey(),
                            'return' => $return,
                        ]),
                        'formmethod' => 'POST',
                        'formnovalidate' => true,
                    ],
                    false,
                )
                ->getAttributes(),
        )
    }}

    {{ $after ?? '' }}
@else
    {{ $unauthorized ?? '' }}
@endif
