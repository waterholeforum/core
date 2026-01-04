<x-waterhole::html :$title :$assets :$seo {{ $attributes }}>
    <x-slot name="head">{{ $head ?? '' }}</x-slot>

    <div class="waterhole" data-controller="page">
        <a href="#main" class="skip-link">
            {{ __('waterhole::system.skip-to-main-content-link') }}
        </a>

        @components(resolve(\Waterhole\Extend\Ui\Layout::class)->before)

        <main id="main" class="waterhole__main" tabindex="-1">
            {{ $slot }}
        </main>

        @components(resolve(\Waterhole\Extend\Ui\Layout::class)->after)
    </div>
</x-waterhole::html>
