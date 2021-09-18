@props(['title' => null, 'breadcrumb' => null])

<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
  <x-waterhole::head :title="$title"/>
</head>

<body>

<div id="waterhole">
  @components(Waterhole\Extend\LayoutBefore::getComponents(), compact('breadcrumb'))

  {{ $slot }}

  @components(Waterhole\Extend\LayoutAfter::getComponents())
</div>

</body>
</html>
