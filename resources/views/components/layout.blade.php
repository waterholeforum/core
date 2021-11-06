<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <x-waterhole::head :title="$title"/>
</head>

<body class="{{ Auth::check() ? 'logged-in' : 'not-logged-in' }}">

<div id="waterhole" data-controller="page">
    <a href="#main" class="skip-link">Skip to main content</a>

    @components(Waterhole\Extend\LayoutBefore::getComponents())

    <main id="main" tabindex="-1">
        {{ $slot }}
    </main>

    @components(Waterhole\Extend\LayoutAfter::getComponents())
</div>

<ui-modal
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

<template id="fetch-error">
    <x-waterhole::alert type="danger" dismissible>
        Something went wrong! Please reload the page and try again.
    </x-waterhole::alert>
</template>

</body>
</html>
