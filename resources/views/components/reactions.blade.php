@php use Illuminate\View\ComponentAttributeBag; @endphp

<x-waterhole::action-form
    :for="$model"
    :action="Waterhole\Actions\React::class"
    :return="$model->url"
    {{ $attributes->class('reactions row wrap gap-xxs') }}
>
    @foreach ($reactionTypes as $reactionType)
        @php $reactions = $reactionsByType[$reactionType->id] ?? collect() @endphp
        <{{ $component->isAuthorized ? 'button' : 'span' }} {{ (new ComponentAttributeBag([
            'name' => 'reaction_type_id',
            'value' => $reactionType->id,
            'data-reaction-type' => $reactionType->id,
            'data-count' => $reactions->count(),
        ]))->class([
            'btn btn--sm btn--outline reaction',
            'is-active' => $reactions->contains('user_id', Auth::id()),
            'is-inert' => !$component->isAuthorized,
        ]) }}>
            <x-waterhole::icon :icon="$reactionType->icon"/>
            <span>{{ $reactions->count() }}</span>

            <ui-tooltip tooltip-class="tooltip tooltip--block">
                <strong>{{ $reactionType->name }}</strong>
                <ul role="list">
                    @foreach ($reactions->take(20) as $reaction)
                        <li>{{ $reaction->user->name }}</li>
                    @endforeach
                    @if ($reactions->count() > 20)
                        <li>{{ __('waterhole::system.user-list-overflow', ['count' => $reactions->count() - 20]) }}</li>
                    @endif
                </ul>
            </ui-tooltip>
        </{{ $component->isAuthorized ? 'button' : 'span' }}>
    @endforeach

    @if ($component->isAuthorized && $reactionSet->reactionTypes->count() > 1)
        <ui-popup placement="top" class="js-only">
            <button class="btn btn--sm btn--icon btn--transparent control">
                <x-waterhole::icon icon="tabler-mood-plus"/>
            </button>

            <ui-menu class="menu reactions-menu" hidden>
                @foreach ($reactionSet->reactionTypes as $reactionType)
                    <button
                        class="text-xl reaction-type-{{ $reactionType->id }}"
                        name="reaction_type_id"
                        value="{{ $reactionType->id }}"
                        role="menuitemradio"
                    >
                        <x-waterhole::icon :icon="$reactionType->icon"/>
                        <ui-tooltip>{{ $reactionType->name }}</ui-tooltip>
                    </button>
                @endforeach
            </ui-menu>
        </ui-popup>
    @endif
</x-waterhole::action-form>
