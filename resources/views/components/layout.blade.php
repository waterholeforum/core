@props(['title' => null])

<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
  <x-waterhole::layout.head :title="$title"/>
</head>

<body>

<div id="waterhole">
  @components(Waterhole\Extend\LayoutBefore::getComponents())

  {{ $slot }}

  @components(Waterhole\Extend\LayoutAfter::getComponents())
</div>

</body>
</html>
