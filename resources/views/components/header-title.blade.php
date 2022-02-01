<div>
    <a href="{{ route('waterhole.home') }}" class="forum-title" style="display: inline-block">
      @if ($logo = config('waterhole.design.logo_url'))
        <img src="{{ $logo }}" alt="{{ config('waterhole.forum.name') }}">
      @else
        {{ config('waterhole.forum.name') }}
      @endif
    </a>

    <span
        class="header-breadcrumb"
        data-page-target="breadcrumb"
        hidden
        data-turbo-permanent
    ></span>
</div>
