@includeIf('waterhole.index-hero')

<div class="section container with-sidebar-start index-layout">
    <div class="sidebar--sticky">
        @components(Waterhole\Extend\IndexNav::getComponents())
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
