<div class="section container with-sidebar index-layout">
    <div class="sidebar sidebar--sticky gap-lg">
        @components(Waterhole\Extend\IndexSidebar::build(), compact('channel'))
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
