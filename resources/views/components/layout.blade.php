<x-waterhole::html :$title :$assets {{ $attributes }}>
    <x-slot name="head">{{ $head ?? '' }}</x-slot>

    <div class="waterhole" data-controller="page">
        <a href="#main" class="skip-link">
            {{ __('waterhole::system.skip-to-main-content-link') }}
        </a>

        @components(Waterhole\Extend\LayoutBefore::build())

        <main id="main" class="waterhole__main" tabindex="-1">
            {{ $slot }}
        </main>

        @components(Waterhole\Extend\LayoutAfter::build())
    </div>
</x-waterhole::html>
