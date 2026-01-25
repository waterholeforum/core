<div class="stack gap-lg">
    <h1 class="h4">{{ $title }}</h1>

    <div class="stack gap-md">
        @foreach ($reasons as $reason)
            <label class="choice">
                <input
                    type="radio"
                    name="reason"
                    value="{{ $reason }}"
                    @checked(old('reason') === $reason)
                    @if ($loop->first) required @endif
                />
                <span class="stack gap-xxs">
                    <span>
                        {{
                            Lang::has($key = "waterhole::forum.report-reason-$reason-label")
                                ? __($key)
                                : Str::headline($reason)
                        }}
                    </span>
                    @if (Lang::has($key = "waterhole::forum.report-reason-$reason-description"))
                        <small class="field__description">
                            {{ __($key) }}
                        </small>
                    @endif
                </span>
            </label>
        @endforeach
    </div>

    <textarea
        name="note"
        rows="3"
        placeholder="{{ __('waterhole::forum.report-note-placeholder') }}"
    >
{{ old('note') }}</textarea
    >
</div>
