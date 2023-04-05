<x-waterhole::cp :title="__('waterhole::cp.reactions-title')">
    <div class="stack gap-md">
        <div class="row gap-md">
            <h1 class="h3">{{ __('waterhole::cp.reactions-title') }}</h1>

            <div class="grow"></div>

            <a href="{{ route('waterhole.cp.reaction-sets.create') }}" type="button" class="btn bg-accent">
                @icon('tabler-plus')
                <span>{{ __('waterhole::cp.create-reaction-set-button') }}</span>
            </a>
        </div>

        <ul class="card" role="list">
            @forelse ($reactionSets as $reactionSet)
                <li class="card__row row gap-xs">
                    <div class="row reverse text-md reactions-condensed">
                        @foreach ($reactionSet->reactionTypes->reverse() as $reactionType)
                            @icon($reactionType->icon)
                        @endforeach
                    </div>

                    {{ $reactionSet->name }}

                    <div class="grow"></div>

                    <x-waterhole::action-buttons
                        class="row text-xs"
                        :for="$reactionSet"
                        placement="bottom-end"
                        :button-attributes="['class' => 'btn btn--icon btn--transparent']"
                        tooltips
                        :limit="2"
                    />
                </li>
            @empty
                <li class="placeholder">No Reaction Sets</li>
            @endforelse
        </ul>
    </div>
</x-waterhole::cp>
