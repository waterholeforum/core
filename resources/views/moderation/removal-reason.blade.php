<div class="stack gap-lg">
    <h1 class="h4">{{ __('waterhole::forum.remove-content-title') }}</h1>

    <div class="field">
        <div class="field__label">{{ __('waterhole::forum.removal-reason-label') }}</div>

        <div class="stack gap-md">
            <label class="choice">
                <input
                    type="radio"
                    name="deleted_reason"
                    value=""
                    @checked(! old('deleted_reason'))
                />
                {{ __('waterhole::forum.removal-reason-unspecified-label') }}
            </label>

            @foreach ($reasons as $reason)
                <label class="choice">
                    <input
                        type="radio"
                        name="deleted_reason"
                        value="{{ $reason }}"
                        @checked(old('deleted_reason') === $reason)
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
    </div>
</div>
