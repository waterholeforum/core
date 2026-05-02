<x-waterhole::html
    :$title
    :$assets
    :$seo
    {{ $attributes->merge(['data-global-sidebar' => $globalSidebar ? 'true' : 'false']) }}
>
    <x-slot name="head">{{ $head ?? '' }}</x-slot>

    <div class="waterhole" data-controller="page">
        <a href="#main" class="btn btn--sm bg-accent skip-link" data-turbo="false">
            {{ __('waterhole::system.skip-to-main-content-link') }}
        </a>

        @components(resolve(\Waterhole\Extend\Ui\Layout::class)->before)

        @if ($globalSidebar && isset($sidebar) && $sidebar->isNotEmpty())
            <div class="waterhole__body with-sidebar with-sidebar--flush grow">
                <aside class="sidebar sidebar--sticky">
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
