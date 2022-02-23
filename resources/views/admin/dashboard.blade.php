<x-waterhole::admin :title="__('waterhole::admin.dashboard-title')">
    <div class="admin-dashboard">
        @foreach (config('waterhole.admin.widgets', []) as $id => $widget)
            <div style="
                --admin-dashboard-widget-width: {{ $widget['width'] ?: 100 }}%;
                --admin-dashboard-widget-height: {{ $widget['height'] ?? 'auto' }}
            ">
                @if (empty($widget['component']::$lazy))
                    @include('waterhole::admin.widget')
                @else
                    <turbo-frame
                        id="widget_{{ $id }}"
                        src="{{ route('waterhole.admin.dashboard.widget', compact('id')) }}"
                        data-controller="turbo-frame"
                        data-action="turbo:frame-load->turbo-frame#removeSrc"
                    >
                        <div class="card">
                            <div class="loading-indicator"></div>
                        </div>
                    </turbo-frame>
                @endif
            </div>
        @endforeach
    </div>
</x-waterhole::admin>
