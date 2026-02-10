<x-waterhole::layout>
    <div class="section container">
        <div class="dialog dialog--sm dialog__body">
            <turbo-frame id="actions">
                <form action="{{ route('waterhole.actions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="actionable" value="{{ request('actionable') }}" />
                    <input type="hidden" name="id[]" value="{{ request('id') }}" />
                    @if ($return = request('return'))
                        <input type="hidden" name="return" value="{{ $return }}" />
                    @endif

                    @foreach ($actions as $action)
                        @if ($action instanceof Waterhole\Actions\Action)
                            {{
                                $action
                                    ->withRenderType(Waterhole\Actions\Action::TYPE_MENU_ITEM)
                                    ->render($models, [
                                        'class' => 'menu-item',
                                        'role' => 'menuitem',
                                        'data-turbo-frame' => '_parent',
                                    ])
                            }}
                        @else
                            {{ $action->render() }}
                        @endif
                    @endforeach
                </form>
            </turbo-frame>
        </div>
    </div>
</x-waterhole::layout>
