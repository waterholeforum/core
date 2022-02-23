<div class="admin-header stack gap-xs">
    @if ($parentTitle)
        <div class="color-muted">
            <a href="{{ $parentUrl }}">{{ $parentTitle }}</a> ›
        </div>
    @endif
    <h1 class="h2">{{ $title }}</h1>
</div>
