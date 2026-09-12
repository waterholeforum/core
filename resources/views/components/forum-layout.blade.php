<x-waterhole::layout
    :$title
    :$assets
    :$seo
    :$rss
    :global-sidebar="config('waterhole.design.global_sidebar')"
    :show-sidebar="$showSidebar"
    {{ $attributes->class('forum-layout') }}
>
    <x-slot:head>
        {{ $head ?? '' }}
    </x-slot:head>

    @if (config('waterhole.design.global_sidebar'))
        <x-slot:sidebar>
            @components(resolve(\Waterhole\Extend\Ui\IndexPage::class)->sidebar, compact('activeNode'))
        </x-slot:sidebar>
    @endif

    {{ $slot }}
</x-waterhole::layout>
