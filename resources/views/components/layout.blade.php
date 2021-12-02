<!doctype html>
<html lang="{{ config('app.locale') }}" class="no-js" data-controller="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="theme-color" content="{{ config('waterhole.design.accent_color') }}">

    <title>{{ $title ? $title.' - ' : '' }}{{ config('waterhole.forum.title', 'Waterhole') }}</title>

    <script>
      document.documentElement.className = document.documentElement.className.replace('no-js', 'js');

      @if (config('waterhole.design.support_dark_mode'))
        document.documentElement.dataset.theme = localStorage.getItem('theme')
          || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      @endif
    </script>

    @foreach (Waterhole\Extend\Stylesheet::urls(['forum', 'forum-'.App::getLocale(), ...$assets]) as $url)
        <link href="{{ $url }}" rel="stylesheet" data-turbo-track="reload">
    @endforeach

    @foreach (Waterhole\Extend\Script::urls(['forum', 'forum-'.App::getLocale(), ...$assets]) as $url)
        <script src="{{ $url }}" defer data-turbo-track="reload"></script>
    @endforeach

    <script>
      window.Waterhole = @json([
        'userId' => Auth::id(),
    ]);
    </script>

    @components(Waterhole\Extend\DocumentHead::getComponents(), compact('title', 'assets'))

    @includeIf('waterhole.head')
</head>

<body class="{{ Auth::check() ? 'logged-in' : 'not-logged-in' }}">

<div id="waterhole" data-controller="page">
    <a href="#main" class="skip-link">Skip to main content</a>

    @includeIf('waterhole.layout-before')

    @components(Waterhole\Extend\LayoutBefore::getComponents())

    <main id="main" tabindex="-1">
        {{ $slot }}
    </main>

    @components(Waterhole\Extend\LayoutAfter::getComponents())

    @includeIf('waterhole.layout-after')
</div>

<ui-modal
    id="modal-element"
    data-controller="modal"
    hidden
    class="modal"
    data-action="turbo:before-stream-render@document->modal#hide turbo:before-render@document->modal#hide"
    data-turbo-permanent
>
    <turbo-frame
        id="modal"
        class="modal__frame"
        data-modal-target="frame"
        data-action="turbo:before-fetch-request->modal#loading turbo:frame-render->modal#loaded"
        aria-labelledby="dialog-title"
        disabled
    >
        <div data-modal-target="loading" class="dialog dialog--sm">
            <div class="loading-indicator"></div>
        </div>
    </turbo-frame>
</ui-modal>

<ui-alerts
    id="alerts"
    class="alerts"
    data-turbo-permanent
    data-controller="alerts"
></ui-alerts>

@if (session('success'))
    <ui-alerts class="alerts js-hidden" data-controller="alerts-append">
        <x-waterhole::alert type="success">
            {{ session('success') }}
        </x-waterhole::alert>
    </ui-alerts>
@endif

<template id="fetch-error">
    <x-waterhole::alert type="danger" dismissible>
        Something went wrong! Please reload the page and try again.
    </x-waterhole::alert>
</template>

</body>
</html>
