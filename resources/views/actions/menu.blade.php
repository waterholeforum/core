<x-waterhole::layout>
    <div class="section container">
        <div class="dialog dialog--sm dialog__body">
            <turbo-frame id="actions">
                @foreach ($actions as $action)
                    @if ($action instanceof Waterhole\Actions\Action)
                        {{
                            $action->withRenderType(Waterhole\Actions\Action::TYPE_MENU_ITEM)->render($models, [
                                'class' => 'menu-item',
                                'role' => 'menuitem',
                                'data-turbo-frame' => '_parent',
                                'form' => 'action-form',
                                'formaction' => route(
                                    'waterhole.actions.store',
                                    request()->only(['actionable', 'id', 'return', 'context']),
                                ),
                                'formmethod' => 'POST',
                                'formnovalidate' => true,
                            ])
                        }}
                    @else
                        {{ $action->render() }}
                    @endif
                @endforeach
            </turbo-frame>
        </div>
    </div>
</x-waterhole::layout>
