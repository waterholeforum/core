<x-waterhole::layout
    :title="$title"
    :assets="array_values(array_unique(['cp', ...$assets]))"
    global-sidebar
    {{ $attributes->class('cp-layout') }}
>
    <x-slot name="head">{{ $head ?? '' }}</x-slot>

    <x-slot:sidebar>
        <x-waterhole::collapsible-nav
            :components="Waterhole\build_components([
                ...resolve(\Waterhole\Extend\Ui\CpNav::class)->items(),
                Waterhole\View\Components\Cp\Version::class,
            ])"
        />
    </x-slot>

    <div hidden data-page-target="title">{{ __('waterhole::cp.title') }}</div>

    <div class="cp-layout__content section container">
        {{ $slot }}
    </div>
</x-waterhole::layout>
