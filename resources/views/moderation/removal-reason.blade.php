<div class="stack gap-lg">
    <h1 class="h4">{{ __('waterhole::forum.remove-content-title') }}</h1>

    <div class="field">
        <div class="field__label">{{ __('waterhole::forum.removal-reason-label') }}</div>

        <div class="stack gap-xs">
            <label class="choice">
                <input
                    type="radio"
                    name="deleted_reason"
                    value=""
                    @checked(! old('deleted_reason'))
                />
                {{ __('waterhole::forum.report-reason-unspecified') }}
            </label>

            @foreach ($reasons as $reason)
                <label class="choice">
                    <input
                        type="radio"
                        name="deleted_reason"
                        value="{{ $reason }}"
                        @checked(old('deleted_reason') === $reason)
                    />
                    {{
                        Lang::has($key = "waterhole::forum.report-reason-$reason")
                            ? __($key)
                            : Str::headline($reason)
                    }}
                </label>
            @endforeach
        </div>
    </div>
</div>
