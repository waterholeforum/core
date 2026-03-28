<div {{ $attributes->class('post-sidebar text-xs') }}>
    @auth
        <x-waterhole::action-button
            :for="$post"
            :action="Waterhole\Actions\Bookmark::class"
            class="btn btn--transparent btn--narrow hide-sm"
        />
    @endauth

    <x-waterhole::action-menu
        :for="$post"
        :button-attributes="['class' => 'btn btn--transparent btn--narrow btn--icon-sm']"
        placement="bottom-start"
    >
        <x-slot name="button">
            @icon('tabler-dots-circle-horizontal')
            <span>{{ __('waterhole::system.controls-button') }}</span>
            <ui-tooltip>
                {{ __('waterhole::system.controls-button') }}
                <x-waterhole::shortcut-label shortcut="selection.actions" />
            </ui-tooltip>
        </x-slot>
    </x-waterhole::action-menu>

    @components(resolve(\Waterhole\Extend\Ui\PostPage::class)->sidebar, compact('post'))
</div>
