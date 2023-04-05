<x-waterhole::cp :title="__('waterhole::cp.taxonomies-title')">
    <div class="stack gap-md">
        <div class="row gap-md">
            <h1 class="h3">{{ __('waterhole::cp.taxonomies-title') }}</h1>

            <div class="grow"></div>

            <a href="{{ route('waterhole.cp.taxonomies.create') }}" type="button" class="btn bg-accent">
                @icon('tabler-plus')
                <span>{{ __('waterhole::cp.create-taxonomy-button') }}</span>
            </a>
        </div>

        <ul class="card" role="list">
            @forelse ($taxonomies as $taxonomy)
                <li class="card__row row gap-xs">
                    {{ $taxonomy->name }}

                    <div class="grow"></div>

                    <x-waterhole::action-buttons
                        class="row text-xs"
                        :for="$taxonomy"
                        :button-attributes="['class' => 'btn btn--icon btn--transparent']"
                        tooltips
                        :limit="2"
                        placement="bottom-end"
                    />
                </li>
            @empty
                <li class="placeholder">No Taxonomies</li>
            @endforelse
        </ul>
    </div>
</x-waterhole::cp>
