<div class="section container with-sidebar index-layout">
    <div class="index-sidebar sidebar sidebar--sticky gap-x-md gap-y-lg">
        @components(Waterhole\Extend\IndexSidebar::build(), compact('channel'))
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
