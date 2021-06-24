<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
  @include('waterhole::partials.head')
</head>

<body>

<div id="waterhole">

{{--  @include('statamic::partials.session-expiry')--}}
{{--  @include('statamic::partials.licensing-alerts')--}}

  @include('waterhole::partials.global-header')

  <div class="container">
    @yield('content')
  </div>

</div>

@include('waterhole::partials.foot')

</body>
</html>
