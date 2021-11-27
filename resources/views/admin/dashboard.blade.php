<x-waterhole::admin title="Dashboard">
    <div class="admin-dashboard">
        @foreach (config('waterhole.admin.widgets', []) as $widget)
            <div style="--admin-dashboard-widget-width: {{ $widget['width'] ?: 100 }}%">
                @components([$widget['component']], $widget)
            </div>
        @endforeach
    </div>
</x-waterhole::admin>
