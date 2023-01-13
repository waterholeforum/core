<x-waterhole::admin :title="__('waterhole::admin.taxonomies-title')">
    <div class="stack gap-md">
        <div class="row gap-md">
            <h1 class="h3">{{ __('waterhole::admin.taxonomies-title') }}</h1>

            <div class="grow"></div>

            <a href="{{ route('waterhole.admin.taxonomies.create') }}" type="button" class="btn bg-accent">
                <x-waterhole::icon icon="tabler-plus"/>
                <span>{{ __('waterhole::admin.create-taxonomy-button') }}</span>
            </a>
        </div>

        <ul class="card" role="list">
            @forelse ($taxonomies as $taxonomy)
                <li class="card__row row gap-xs">
                    {{ $taxonomy->name }}

                    <div class="grow"></div>

                    <x-waterhole::action-buttons
                        :for="$taxonomy"
                        icons
                        class="row text-xs"
                        :button-attributes="['class' => 'btn btn--icon btn--transparent']"
                        placement="bottom-end"
                    />
                </li>
            @empty
                <li class="placeholder">No Taxonomies</li>
            @endforelse
        </ul>
    </div>
</x-waterhole::admin>
