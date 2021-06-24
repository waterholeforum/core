<a href="{{ route('waterhole.home') }}">
  @if ($customLogo = config('waterhole.forum.custom_logo_url'))
    <img src="{{ $customLogo }}" alt="{{ config('waterhole.forum.title') }}">
  @else
    {{ config('waterhole.forum.title') }}
  @endif
</a>
