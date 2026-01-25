<div class="removed-banner color-muted stack gap-xs full-width">
    <div class="removed-banner__summary row gap-sm align-center">
        {{ $lead ?? '' }}

        @if ($subject->deleted_reason)
            <span class="text-xxs">
                {{
                    Lang::has($key = "waterhole::forum.report-reason-$subject->deleted_reason-label")
                        ? __($key)
                        : Str::headline($subject->deleted_reason)
                }}
            </span>
        @endif

        @if ($canModerate = $subject->canModerate(Auth::user()))
            <div class="text-xxs push-end">
                @if ($subject->deletedBy)
                    <span>{{ __('waterhole::forum.removed-by-label') }}</span>
                    <x-waterhole::user-label :user="$subject->deletedBy" />
                @endif

                <x-waterhole::relative-time :datetime="$subject->deleted_at" />
            </div>
        @endif

        {{ $actions ?? '' }}
    </div>

    @if ($subject->deleted_message && (Auth::id() === $subject->user_id || $canModerate))
        <div class="removed-banner__details stack gap-xs text-xxs">
            <div class="stack gap-xxs">
                <span>{!! nl2br(e($subject->deleted_message)) !!}</span>
            </div>
        </div>
    @endif
</div>
