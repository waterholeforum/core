<x-waterhole::layout>
    <turbo-frame id="modal">
        <x-waterhole::dialog
            :title="$confirmation"
            class="dialog--sm confirm-action"
        >
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
                        <a
                            href="{{ old('return', url()->previous()) }}"
                            class="btn"
                            data-action="modal#hide"
                        >Cancel</a>

                        <button
                            type="submit"
                            name="confirmed"
                            value="1"
                            class="btn {{ $action->destructive ? 'btn--danger' : 'btn--primary' }} btn--wide"
                        >{{ $action->buttonText($items) }}</button>
                    </div>
                </div>
            </form>
        </x-waterhole::dialog>
    </turbo-frame>
</x-waterhole::layout>
