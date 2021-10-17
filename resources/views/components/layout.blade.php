@props(['title' => null])

<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
  <x-waterhole::head :title="$title"/>
</head>

<body>

<div id="waterhole">
  <a href="#main" class="skip-link">Skip to main content</a>

  @components(Waterhole\Extend\LayoutBefore::getComponents())

  <main id="main" tabindex="-1">
    {{ $slot }}
  </main>

  @components(Waterhole\Extend\LayoutAfter::getComponents())
</div>

<ui-modal data-controller="modal" hidden class="modal">
  <turbo-frame
      id="modal"
      class="modal__frame"
      data-modal-target="frame"
      data-action="turbo:before-fetch-request->modal#loading turbo:frame-render->modal#loaded"
  ></turbo-frame>

  <div data-modal-target="loading" class="dialog dialog--sm">
    <div class="loading-indicator"></div>
  </div>
</ui-modal>

</body>
</html>
