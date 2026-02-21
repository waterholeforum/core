<button
    type="submit"
    name="action_class"
    value="{{ $followable->isIgnored() ? Waterhole\Actions\Ignore::class : Waterhole\Actions\Follow::class }}"
    form="action-form"
    formaction="{{
        route('waterhole.actions.store', [
            'actionable' => get_class($followable),
            'id' => $followable->getKey(),
            'return' => request()->fullUrl(),
        ])
    }}"
    formmethod="POST"
    formnovalidate
    {{ $attributes->class(['btn block', 'bg-warning-soft' => $followable->isFollowed()]) }}
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
