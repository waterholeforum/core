<x-waterhole::layout>
    <turbo-frame id="modal">
        <div class="dialog dialog--sm confirm-action">
            <header class="dialog__header">
                <h1 class="dialog__title">{{ $confirmation }}</h1>
            </header>

            <div class="dialog__body">
                <form action="{{ route('waterhole.action.store') }}" method="POST">
                    @csrf

                    <input
                        type="hidden"
                        name="action_class"
                        value="{{ get_class($action) }}"
                    >
                    <input
                        type="hidden"
                        name="actionable"
                        value="{{ $actionable }}"
                    >
                    <input
                        type="hidden"
                        name="return"
                        value="{{ old('return', url()->previous()) }}"
                    >

                    @foreach ($items as $item)
                        <input type="hidden" name="id[]" value="{{ $item->id }}">
                    @endforeach

                    <div class="form">
                        <x-waterhole::validation-errors :errors="$errors"/>

                        {{ $confirmationBody }}

                        <div class="toolbar toolbar--right">
                            <button
                                type="submit"
                                name="confirmed"
                                value="1"
                                class="btn {{ $action->destructive ? 'btn--danger' : 'btn--primary' }}"
                            >{{ $action->buttonText($items) }}</button>

                            <a
                                href="{{ old('return', url()->previous()) }}"
                                class="btn"
                                data-action="modal#hide"
                            >Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </turbo-frame>
</x-waterhole::layout>
