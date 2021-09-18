@props(['breadcrumb' => null])

<div>
    <a href="{{ route('waterhole.home') }}" class="forum-title" style="display: inline-block">
      @if ($customLogo = config('waterhole.forum.custom_logo_url'))
        <img src="{{ $customLogo }}" alt="{{ config('waterhole.forum.title') }}">
      @else
        {{ config('waterhole.forum.title') }}
      @endif
    </a>

    @if ($breadcrumb)
        <span class="header-breadcrumb">
            {{ $breadcrumb }}
        </span>
    @endif
</div>
