<x-waterhole::layout :title="$title">
    <h1 hidden data-page-target="title">Administration</h1>

    <div class="section container with-sidebar-start">
        <nav class="sidebar--sticky">
            @components(Waterhole\Extend\AdminNav::getComponents())
        </nav>

        <div>
            {{ $slot }}
        </div>
    </div>
</x-waterhole::layout>
