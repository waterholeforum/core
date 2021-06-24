<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
  @include('waterhole::partials.head')
</head>

<body>

<div id="waterhole">

  <div class="container">
    <x-waterhole::header.title/>

    @yield('content')
  </div>

</div>

@include('waterhole::partials.foot')

</body>
</html>
