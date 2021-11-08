<meta charset="utf-8">
<meta name="viewport" content="width=device-width">

<title>{{ $title ? $title.' - ' : '' }}{{ config('waterhole.forum.title', 'Waterhole') }}</title>

<script>
  document.documentElement.className = document.documentElement.className.replace('no-js', 'js');
</script>

@foreach (Waterhole\Extend\Stylesheet::urls(['*', 'forum', 'forum-'.App::getLocale()]) as $url)
    <link href="{{ $url }}" rel="stylesheet" data-turbo-track="reload">
@endforeach

@foreach (Waterhole\Extend\Script::urls(['*', 'forum', 'forum-'.App::getLocale()]) as $url)
    <script src="{{ $url }}" defer data-turbo-track="reload"></script>
@endforeach

@components(Waterhole\Extend\DocumentHead::getComponents(), ['title' => $title])
