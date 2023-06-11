<div {{ $attributes->class('row wrap gap-sm') }}>
    <x-waterhole::action-menu
        :for="$post"
        class="grow"
        :button-attributes="['class' => 'btn full-width']"
        placement="bottom-end"
    >
        <x-slot name="button">
            @icon('tabler-settings')
            <span>{{ __('waterhole::system.controls-button') }}</span>
            @icon('tabler-chevron-down')
        </x-slot>
    </x-waterhole::action-menu>

    <div class="hide-sm grow">
        <x-waterhole::follow-button :followable="$post" />
    </div>

    @components(Waterhole\Extend\PostSidebar::build(), compact('post'))
</div>
