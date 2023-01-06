<x-waterhole::action-form
    :for="$model"
    :action="Waterhole\Actions\React::class"
    :return="$model->url"
>
    <ui-popup placement="top">
        <button class="btn btn--sm btn--transparent control">
            <x-waterhole::icon icon="tabler-mood-smile"/>
            React
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
</x-waterhole::action-form>
