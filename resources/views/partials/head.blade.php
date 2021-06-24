<meta charset="utf-8">
<meta name="viewport" content="width=device-width">

<title>@hasSection('title') @yield('title') - @endif {{ config('waterhole.forum.title', 'Waterhole') }}</title>

{{--<link href="{{ Statamic::cpAssetUrl('css/cp.css') }}?v={{ Statamic::version() }}" rel="stylesheet">--}}

{{--@if (Statamic::pro() && config('statamic.cp.custom_css_url'))--}}
{{--  <link href="{{ config('statamic.cp.custom_css_url') }}?v={{ Statamic::version() }}" rel="stylesheet">--}}
{{--@endif--}}

{{--@foreach (Statamic::availableExternalStyles(request()) as $url)--}}
{{--  <link href="{{ $url }}" rel="stylesheet" />--}}
{{--@endforeach--}}

{{--@foreach (Statamic::availableStyles(request()) as $package => $paths)--}}
{{--  @foreach ($paths as $path)--}}
{{--    <link href="{{ Statamic::vendorAssetUrl("$package/css/$path") }}" rel="stylesheet" />--}}
{{--  @endforeach--}}
{{--@endforeach--}}

@stack('head')
