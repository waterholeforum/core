<header class="cp-title stack gap-xs">
    @if ($parentTitle)
        <ol class="breadcrumb">
            <li><a href="{{ $parentUrl }}">{{ $parentTitle }}</a></li>
            <li aria-hidden="true"></li>
        </ol>
    @endif

    <h1 class="h3">{{ $title }}</h1>
</header>
