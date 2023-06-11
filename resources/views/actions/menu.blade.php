<x-waterhole::layout>
    <div class="section container">
        <div class="dialog dialog--sm dialog__body">
            <turbo-frame id="actions">
                <form action="{{ route('waterhole.actions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="actionable" value="{{ request('actionable') }}" />
                    <input type="hidden" name="id[]" value="{{ request('id') }}" />

                    @foreach ($actions as $action)
                        {{ $action->render($models, ['class' => 'menu-item', 'role' => 'menuitem']) }}
                    @endforeach
                </form>
            </turbo-frame>
        </div>
    </div>
</x-waterhole::layout>
