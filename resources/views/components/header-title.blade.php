<a href="{{ route('waterhole.home') }}" class="forum-title">
  @if ($logo = config('waterhole.design.logo_url'))
    <img src="{{ $logo }}" alt="{{ config('waterhole.forum.name') }}">
  @else
    {{ config('waterhole.forum.name') }}
  @endif
</a>
