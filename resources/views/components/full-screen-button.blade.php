<div {{ $attributes->class('full-screen-button') }}>
    <x-waterhole::text-editor-button
        shortcut="editor.full-screen"
        class="full-screen-button__enter"
        data-action="full-screen#toggleFullScreen"
        :label="__('waterhole::system.full-screen-enter-button')"
        icon="tabler-arrows-diagonal"
    />

    <button
        type="button"
        class="btn btn--transparent btn--icon full-screen-button__exit"
        data-action="full-screen#toggleFullScreen"
        data-shortcut-trigger="editor.full-screen navigation.close"
    >
        @icon('tabler-arrows-diagonal-minimize-2')
        <ui-tooltip>
            {{ __('waterhole::system.full-screen-exit-button') }}
            <x-waterhole::shortcut-label shortcut="editor.full-screen" />
        </ui-tooltip>
    </button>
</div>
