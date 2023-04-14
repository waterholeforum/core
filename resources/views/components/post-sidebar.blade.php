<div {{ $attributes->class('row wrap gap-sm') }}>
    <x-waterhole::action-menu :for="$post" class="grow">
        <x-slot:button>
            <button type="button" class="btn block">
                @icon('tabler-settings')
                <span>{{ __('waterhole::system.controls-button') }}</span>
                @icon('tabler-chevron-down')
            </button>
        </x-slot:button>
    </x-waterhole::action-menu>

    <div class="hide-sm grow">
        <x-waterhole::follow-button :followable="$post"/>
    </div>
</div>
