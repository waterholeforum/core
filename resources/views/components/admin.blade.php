<x-waterhole::layout
    :title="$title"
    :assets="['admin']"
    {{ $attributes->class('admin') }}
>
    <div hidden data-page-target="title">{{ __('waterhole::admin.admin-title') }}</div>

    <div class="admin__layout section container with-sidebar">
        <nav class="admin__nav sidebar sidebar--sticky">
            @components(Waterhole\Extend\AdminNav::build())
        </nav>

        <div class="admin__content">
            {{ $slot }}
        </div>
    </div>
</x-waterhole::layout>
