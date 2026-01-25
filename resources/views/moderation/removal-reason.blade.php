<div class="stack gap-md">
    <h1 class="h4">{{ $title }}</h1>

    <details class="card" open>
        <summary class="card__header h5">
            {{ __('waterhole::forum.removal-reason-label') }}
        </summary>
        <div class="card__body stack gap-md">
            <label class="choice">
                <input type="radio" name="deleted_reason" value="" @checked(! $selectedReason) />
                {{ __('waterhole::forum.removal-reason-unspecified-label') }}
            </label>

            @foreach ($reasons as $reason)
                <label class="choice">
                    <input
                        type="radio"
                        name="deleted_reason"
                        value="{{ $reason }}"
                        @checked($selectedReason === $reason)
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
    </details>

    @if ($model->user)
        <details class="card" data-controller="details-focus">
            <summary class="card__header h5">
                {{ __('waterhole::forum.removal-message-label') }}
            </summary>
            <div class="card__body">
                <textarea name="deleted_message" rows="3">{{ old('deleted_message') }}</textarea>
            </div>
        </details>
    @endif

    @if ($model->user && $canSuspend)
        <details class="card">
            <summary class="card__header h5">
                {{ __('waterhole::forum.user-actions-label') }}
            </summary>

            <div class="card__body stack gap-md">
                <div class="stack gap-xs" data-controller="reveal">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="suspend_user"
                            value="1"
                            data-reveal-target="if"
                            @checked(old('suspend_user'))
                        />
                        {{
                            __('waterhole::forum.user-actions-suspend-label', [
                                'user' => Waterhole\username($model->user),
                            ])
                        }}
                    </label>

                    <div
                        class="row gap-xs wrap choice-indent"
                        data-reveal-target="then"
                        data-controller="suspend-duration"
                    >
                        <input
                            type="number"
                            name="suspend_for"
                            min="1"
                            value="{{ old('suspend_for', 7) }}"
                            data-suspend-duration-target="count"
                            style="width: 6ch"
                        />
                        <select
                            name="suspend_unit"
                            data-suspend-duration-target="unit"
                            data-action="suspend-duration#update"
                            style="width: auto"
                        >
                            <option value="days" @selected(old('suspend_unit', 'days') === 'days')>
                                {{ __('waterhole::forum.user-actions-suspend-days') }}
                            </option>
                            <option value="weeks" @selected(old('suspend_unit') === 'weeks')>
                                {{ __('waterhole::forum.user-actions-suspend-weeks') }}
                            </option>
                            <option
                                value="indefinite"
                                @selected(old('suspend_unit') === 'indefinite')
                            >
                                {{ __('waterhole::forum.user-actions-suspend-indefinitely') }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </details>
    @endif
</div>
