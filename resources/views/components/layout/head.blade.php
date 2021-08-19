@props(['title' => null])

<meta charset="utf-8">
<meta name="viewport" content="width=device-width">

<title>{{ $title ? $title.' - ' : '' }}{{ config('waterhole.forum.title', 'Waterhole') }}</title>

@foreach (Waterhole\Extend\Stylesheet::urls(['web', 'web-'.App::getLocale()]) as $url)
    <link href="{{ $url }}" rel="stylesheet">
@endforeach

@foreach (Waterhole\Extend\Script::urls(['web', 'web-'.App::getLocale()]) as $url)
    <script src="{{ $url }}" defer></script>
@endforeach

@components(Waterhole\Extend\DocumentHead::getComponents(), ['title' => $title])
