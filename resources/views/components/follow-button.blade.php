<x-waterhole::action-form :for="$followable" {{ $attributes }}>
    <button
        type="submit"
        name="action_class"
        value="{{ $followable->isFollowed() ? Waterhole\Actions\Unfollow::class : ($followable->isIgnored() ? Waterhole\Actions\Unignore::class : Waterhole\Actions\Follow::class) }}"
        class="{{ $buttonClass }} {{ $followable->isFollowed() ? 'bg-warning-soft color-warning' : '' }}"
    >
        @if ($followable->isFollowed())
            @icon('tabler-bell')
            <span>{{ __('waterhole::forum.follow-button-following') }}</span>
        @elseif ($followable->isIgnored())
            @icon('tabler-eye-off')
            <span>{{ __('waterhole::forum.follow-button-ignored') }}</span>
        @else
            @icon('tabler-bell')
            <span>{{ __('waterhole::forum.follow-button') }}</span>
            <ui-tooltip>{{ __($localePrefix.'-follow-description') }}</ui-tooltip>
        @endif
    </button>
</x-waterhole::action-form>
