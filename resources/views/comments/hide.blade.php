<div class="stack gap-lg">
    <h1 class="h4">{{ __('waterhole::forum.hide-comment-title') }}</h1>

    <div class="field">
        <div class="field__label">{{ __('waterhole::forum.hide-reason-label') }}</div>

        <div class="stack gap-xs">
            <label class="choice">
                <input type="radio" name="hidden_reason" value="" checked />
                {{ __('waterhole::forum.hide-reason-unspecified') }}
            </label>

            @foreach ($reasons as $reason)
                <label class="choice">
                    <input type="radio" name="hidden_reason" value="{{ $reason }}" />
                    {{ __("waterhole::forum.report-reason-$reason") }}
                </label>
            @endforeach
        </div>
    </div>
</div>
