<div>
    <a href="{{ route('waterhole.home') }}" class="forum-title" style="display: inline-block">
      @if ($customLogo = config('waterhole.forum.custom_logo_url'))
        <img src="{{ $customLogo }}" alt="{{ config('waterhole.forum.title') }}">
      @else
        {{ config('waterhole.forum.title') }}
      @endif
    </a>

    <span
        class="header-breadcrumb"
        data-page-target="breadcrumb"
        hidden
        data-turbo-permanent
    ></span>
</div>
