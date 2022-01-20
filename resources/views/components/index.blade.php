@includeIf('waterhole.index-before')

<div class="section container with-sidebar-start index-layout">
    <div class="sidebar--sticky stack-lg">
        @includeIf('waterhole.nav-before')

        @components(Waterhole\Extend\IndexNav::getComponents())
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
