<x-waterhole::layout :title="__('waterhole::system.confirm-action-title')">
    <div class="container section">
        <turbo-frame id="modal">
            <x-waterhole::dialog
                :aria-label="__('waterhole::system.confirm-action-title')"
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

                    <div class="stack gap-xl">
                        <x-waterhole::validation-errors/>

                        @if ($isSimpleContent = (is_string($content = $action->confirm($models)) || is_array($content)))
                            <div class="content">
                                @foreach (Arr::wrap($content) as $paragraph)
                                    <p @if ($loop->first) class="h4" @endif>{{ $paragraph }}</p>
                                @endforeach
                            </div>
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
                                class="btn {{ $action->destructive ? 'bg-danger' : 'bg-accent' }} btn--wide"
                                @if ($isSimpleContent) autofocus @endif
                            >{{ $action->confirmButton($models) }}</button>
                        </div>
                    </div>
                </form>
            </x-waterhole::dialog>
        </turbo-frame>
    </div>
</x-waterhole::layout>
