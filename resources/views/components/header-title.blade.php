<a href="{{ route('waterhole.home') }}" class="header-title nowrap text-lg weight-medium">
  @if ($logo = config('waterhole.design.logo_url'))
    <img src="{{ $logo }}" alt="{{ config('waterhole.forum.name') }}">
  @else
    {{ config('waterhole.forum.name') }}
  @endif
</a>
