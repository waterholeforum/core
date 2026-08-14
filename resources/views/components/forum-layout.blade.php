<x-waterhole::layout
    :$title
    :$assets
    :$seo
    :global-sidebar="config('waterhole.design.global_sidebar')"
    :show-sidebar="$showSidebar"
    {{ $attributes->class('forum-layout') }}
>
    <x-slot name="head">{{ $head ?? '' }}</x-slot>

    @if (config('waterhole.design.global_sidebar'))
        <x-slot:sidebar>
            @components(resolve(\Waterhole\Extend\Ui\IndexPage::class)->sidebar, compact('channel'))
        </x-slot>
    @endif

    {{ $slot }}
</x-waterhole::layout>
