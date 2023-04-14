<div {{ $attributes->class('row') }}>
    <div class="tabs hide-sm">
        @components($firstComponents->all())

        @if (count($overflowComponents))
            <x-waterhole::selector
                :value="$activeComponent"
                :options="$overflowComponents->all()"
                :label="fn($component) => $component->label"
                :href="fn($component) => $component->href"
                button-class="tab"
                placement="bottom-start"
            >
                <x-slot name="button">
                    @icon('tabler-dots', ['aria-label' => __('waterhole::system.more-button')])
                </x-slot>
            </x-waterhole::selector>
        @endif
    </div>

    <div class="tabs hide-md-up">
        <x-waterhole::selector
            class="hide-md-up"
            :value="$activeComponent"
            :options="$components->all()"
            :label="fn($component) => $component->label"
            :href="fn($component) => $component->href"
            button-class="tab"
            placement="bottom-start"
        />
    </div>
</div>

