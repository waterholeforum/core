<x-waterhole::layout :title="__('waterhole::system.confirm-action')">
    <div class="section">
        <turbo-frame id="modal">
            <x-waterhole::dialog
                :aria-label="__('waterhole::system.confirm-action')"
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

                    @foreach ($models as $model)
                        <input type="hidden" name="id[]" value="{{ $model->getKey() }}">
                    @endforeach

                    <div class="form">
                        <x-waterhole::validation-errors/>

                        @if (is_string($content = $action->confirm($models)))
                            <p class="h3">{{ $content }}</p>
                        @else
                            <div>{{ $content }}</div>
                        @endif

                        <div class="row gap-xs wrap justify-end">
                            <a
                                href="{{ old('return', url()->previous()) }}"
                                class="btn"
                                data-action="modal#hide"
                            >{{ __('waterhole::system.cancel-button') }}</a>

                            <button
                                type="submit"
                                name="confirmed"
                                value="1"
                                class="btn {{ $action->destructive ? 'btn--danger' : 'btn--primary' }} btn--wide"
                            >{{ $action->confirmButton($models) }}</button>
                        </div>
                    </div>
                </form>
            </x-waterhole::dialog>
        </turbo-frame>
    </div>
</x-waterhole::layout>
