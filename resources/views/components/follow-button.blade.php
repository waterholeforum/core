<x-waterhole::action-form :for="$followable" {{ $attributes }}>
    <button
        type="submit"
        name="action_class"
        value="{{ $followable->isIgnored() ? Waterhole\Actions\Ignore::class : Waterhole\Actions\Follow::class }}"
        class="{{ $buttonClass }} {{ $followable->isFollowed() ? 'bg-warning-soft' : '' }}"
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
            <ui-tooltip placement="bottom" delay="500">
                {{ __($localePrefix . '-follow-description') }}
            </ui-tooltip>
        @endif
    </button>
</x-waterhole::action-form>
