<div class="section container with-sidebar-start index-layout">
    <div class="sidebar--sticky stack gap-lg">
        @components(Waterhole\Extend\IndexSidebar::build())
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
