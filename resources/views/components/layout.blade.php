<!doctype html>
<html lang="{{ config('app.locale') }}" class="no-js" data-controller="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="theme-color" content="{{ config('waterhole.design.accent_color') }}">

    <title>{{ $title ? $title.' - ' : '' }}{{ config('waterhole.forum.name') }}</title>

    <script>
      document.documentElement.className = document.documentElement.className.replace('no-js', 'js');

      @if (config('waterhole.design.support_dark_mode'))
        document.documentElement.dataset.theme = localStorage.getItem('theme')
          || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      @endif
    </script>

    @foreach (Waterhole\Extend\Stylesheet::urls(['default', 'default-'.App::getLocale(), ...$assets]) as $url)
        <link href="{{ $url }}" rel="stylesheet" data-turbo-track="reload">
    @endforeach

    @foreach (Waterhole\Extend\Script::urls(['default', 'default-'.App::getLocale(), ...$assets]) as $url)
        <script src="{{ $url }}" defer data-turbo-track="reload"></script>
    @endforeach

    <script>
      window.Waterhole = @json([
        'userId' => Auth::id(),
      ]);
    </script>

    @components(Waterhole\Extend\DocumentHead::build(), compact('title', 'assets'))
</head>

<body class="{{ Auth::check() ? 'logged-in' : 'not-logged-in' }}">

<div id="waterhole" data-controller="page">
    <a href="#main" class="skip-link">@lang('waterhole::system.skip-to-main-content')</a>

    @components(Waterhole\Extend\LayoutBefore::build())

    <main id="main" tabindex="-1">
        {{ $slot }}
    </main>

    @components(Waterhole\Extend\LayoutAfter::build())
</div>

{{--
    The persistent modal element contains a Turbo Frame which can be targeted to
    display modal content. It uses a Stimulus controller such that when content
    is loaded into the frame, the modal will be shown, or if the response
    does not contain modal frame content, the modal will be hidden.
--}}
<ui-modal
    class="modal"
    hidden
    data-controller="modal"
    data-action="
        turbo:before-stream-render@document->modal#hide
        turbo:before-render@document->modal#hide"
    data-turbo-permanent
>
    <turbo-frame
        id="modal"
        class="modal__frame"
        data-modal-target="frame"
        data-action="
            turbo:before-fetch-request->modal#loading
            turbo:frame-render->modal#loaded"
        aria-labelledby="dialog-title"
        disabled
    >
        <div class="dialog dialog--sm">
            <div class="loading-indicator"></div>
        </div>
    </turbo-frame>
</ui-modal>

{{--
    The main alerts element, which persists between pages. This element is
    accessible in JavaScript via window.Waterhole.alerts. For API information:
    https://github.com/tobyzerner/inclusive-elements/tree/master/src/alerts
--}}
<ui-alerts
    id="alerts"
    class="alerts"
    data-turbo-permanent
    data-controller="alerts"
></ui-alerts>

{{--
    Here we render session "flash" alerts into a separate alerts container.
    If JavaScript is enabled, this container is hidden, and the alerts-append
    Stimulus controller will append its children to the main alerts element.
--}}
<div class="alerts js-hidden" data-controller="alerts-append">
    @foreach (['success', 'warning', 'error', 'info'] as $type)
        @if (session($type))
            <x-waterhole::alert :type="$type">
                {{ session($type) }}
            </x-waterhole::alert>
        @endif
    @endforeach
</div>

{{--
    Templates for fetch error alert messages. These is cloned into the
    alerts element whenever there is a fetch request error in JavaScript.
--}}
<template id="forbidden-alert">
    <x-waterhole::alert type="danger">
        @lang('waterhole::system.forbidden')
    </x-waterhole::alert>
</template>

<template id="too-many-requests-alert">
    <x-waterhole::alert type="danger">
        @lang('waterhole::system.too-many-requests')
    </x-waterhole::alert>
</template>

<template id="fatal-error-alert">
    <x-waterhole::alert type="danger">
        @lang('waterhole::system.fatal-error')
    </x-waterhole::alert>
</template>

</body>
</html>
