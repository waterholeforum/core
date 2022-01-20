<x-waterhole::layout :title="$title" :assets="['admin']">
    <div hidden data-page-target="title">Administration</div>

    <div class="section container with-sidebar-start">
        <nav class="sidebar--sticky">
            @components(Waterhole\Extend\AdminNav::build())
        </nav>

        <div style="max-width: 90ch; margin-inline: auto">
            {{ $slot }}
        </div>
    </div>
</x-waterhole::layout>
