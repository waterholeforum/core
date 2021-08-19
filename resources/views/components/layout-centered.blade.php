@props(['title' => null])

<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
  <x-waterhole::layout.head :title="$title"/>
</head>

<body>

<div id="waterhole">

  <div class="container">
    <x-waterhole::header.title/>

    {{ $slot }}
  </div>

</div>

</body>
</html>
