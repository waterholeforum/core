<x-waterhole::layout
    :$title
    :$assets
    :$seo
    :global-sidebar="config('waterhole.design.global_sidebar')"
    {{ $attributes->class('forum-layout') }}
>
    <x-slot name="head">{{ $head ?? '' }}</x-slot>

    @if ($showSidebar && config('waterhole.design.global_sidebar'))
        <x-slot:sidebar>
            @components(resolve(\Waterhole\Extend\Ui\IndexPage::class)->sidebar, compact('channel'))
        </x-slot:sidebar>
    @endif

    {{ $slot }}
</x-waterhole::layout>
