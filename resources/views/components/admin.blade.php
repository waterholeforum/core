<x-waterhole::layout
    :title="$title"
    :assets="['admin']"
    {{ $attributes->class('admin') }}
>
    <div hidden data-page-target="title">{{ __('waterhole::admin.admin-title') }}</div>

    <div class="admin__layout section container with-sidebar">
        <div class="admin__sidebar sidebar sidebar--sticky">
            <x-waterhole::responsive-nav
                :components="Waterhole\build_components([
                    ...Waterhole\Extend\AdminNav::build(),
                    Waterhole\View\Components\Admin\Version::class,
                ])"
            />
        </div>

        <div class="admin__content">
            {{ $slot }}
        </div>
    </div>
</x-waterhole::layout>
