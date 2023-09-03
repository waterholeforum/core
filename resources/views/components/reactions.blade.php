@php
    use Illuminate\View\ComponentAttributeBag;
@endphp

<x-waterhole::action-form
    :for="$model"
    :action="Waterhole\Actions\React::class"
    :return="$model->url"
    {{ $attributes->class('reactions row wrap gap-xxs') }}
>
    @foreach ($reactionTypes as $reactionType)
        @php
            $count = $model->reactionsSummary->count($reactionType);
        @endphp

        <{{ $component->isAuthorized ? 'button' : 'span' }}
            {{
                (new ComponentAttributeBag([
                    'name' => 'reaction_type_id',
                    'value' => $reactionType->id,
                    'data-reaction-type' => $reactionType->id,
                    'data-count' => $count,
                ]))->class([
                    'btn btn--sm btn--outline reaction',
                    'is-active' => $model->reactionsSummary->userReacted($reactionType),
                    'is-inert' => ! $component->isAuthorized,
                ])
            }}
        >
            @icon($reactionType->icon)
            <span>{{ $count }}</span>

            <ui-tooltip tooltip-class="tooltip tooltip--block">
                <strong>{{ $reactionType->name }}</strong>
                @if ($count)
                    <turbo-frame
                        id="reactions"
                        src="{{ $model->reactionsUrl($reactionType) }}"
                        loading="lazy"
                    >
                        Loading...
                    </turbo-frame>
                @endif
            </ui-tooltip>
        </{{ $component->isAuthorized ? 'button' : 'span' }}>
    @endforeach

    @if ($component->isAuthorized && $reactionSet->reactionTypes->count() > 1)
        <ui-popup placement="top" class="js-only">
            <button class="btn btn--sm btn--icon btn--transparent control">
                @icon('tabler-mood-plus')
                <ui-tooltip>{{ __('waterhole::forum.add-reaction-button') }}</ui-tooltip>
            </button>

            <ui-menu class="menu reactions-menu" hidden>
                @foreach ($reactionSet->reactionTypes as $reactionType)
                    <button
                        class="text-xl reaction-type-{{ $reactionType->id }}"
                        name="reaction_type_id"
                        value="{{ $reactionType->id }}"
                        role="menuitemradio"
                    >
                        @icon($reactionType->icon)
                        <ui-tooltip>{{ $reactionType->name }}</ui-tooltip>
                    </button>
                @endforeach
            </ui-menu>
        </ui-popup>
    @endif
</x-waterhole::action-form>
