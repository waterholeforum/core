<div>
    <a href="{{ route('waterhole.home') }}" class="forum-title" style="display: inline-block">
      @if ($logo = config('waterhole.design.logo_url'))
        <img src="{{ $logo }}" alt="{{ config('waterhole.forum.title') }}">
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
