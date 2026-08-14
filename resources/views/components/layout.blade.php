<x-waterhole::html
    :$title
    :$assets
    :$seo
    {{ $attributes->merge(['data-global-sidebar' => $globalSidebar ? 'true' : 'false']) }}
>
    <x-slot name="head">{{ $head ?? '' }}</x-slot>

    @php($hasGlobalSidebar = $globalSidebar && isset($sidebar) && $sidebar->isNotEmpty())

    <div
        @class([
            'waterhole',
            'global-sidebar--header-only' => $hasGlobalSidebar && ! $showSidebar,
        ])
        data-controller="page{{ $hasGlobalSidebar ? ' global-sidebar' : '' }}"
    >
        <a
            href="#main"
            class="btn btn--sm bg-accent skip-link"
            data-turbo="false"
        >
            {{ __('waterhole::system.skip-to-main-content-link') }}
        </a>

        @components(resolve(\Waterhole\Extend\Ui\Layout::class)->before)

        @if ($hasGlobalSidebar)
            <div
                class="waterhole__body with-sidebar with-sidebar--flush grow"
                data-global-sidebar-target="body"
            >
                <aside
                    class="global-sidebar sidebar sidebar--sticky"
                    data-global-sidebar-target="sidebar"
                >
                    {{ $sidebar }}
                </aside>

                <main id="main" class="waterhole__main" tabindex="-1">
                    {{ $slot }}
                </main>
            </div>
        @else
            <main id="main" class="waterhole__main" tabindex="-1">
                {{ $slot }}
            </main>
        @endif

        @components(resolve(\Waterhole\Extend\Ui\Layout::class)->after)
    </div>
</x-waterhole::html>
