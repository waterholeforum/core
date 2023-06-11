<x-waterhole::layout :title="$title" :assets="['cp']" {{ $attributes->class('cp') }}>
    <div hidden data-page-target="title">{{ __('waterhole::cp.title') }}</div>

    <div class="cp__layout section container with-sidebar">
        <div class="cp__sidebar sidebar sidebar--sticky">
            <x-waterhole::collapsible-nav
                :components="Waterhole\build_components([
                    ...Waterhole\Extend\CpNav::build(),
                    Waterhole\View\Components\Cp\Version::class,
                ])"
            />
        </div>

        <div class="cp__content">
            {{ $slot }}
        </div>
    </div>
</x-waterhole::layout>
