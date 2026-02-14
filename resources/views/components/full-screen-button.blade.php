@props([
    'hotkeyScope' => null,
])

<div {{ $attributes->class('full-screen-button') }}>
    <x-waterhole::text-editor-button
        hotkey="Meta+Shift+F"
        :id="$hotkeyScope"
        class="full-screen-button__enter"
        data-action="full-screen#toggleFullScreen"
        :label="__('waterhole::system.full-screen-enter-button')"
        icon="tabler-arrows-diagonal"
    />

    <x-waterhole::text-editor-button
        class="full-screen-button__exit"
        data-action="full-screen#toggleFullScreen"
        :label="__('waterhole::system.full-screen-exit-button')"
        icon="tabler-arrows-diagonal-minimize-2"
    />
</div>
