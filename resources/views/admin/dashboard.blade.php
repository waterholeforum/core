<x-waterhole::admin :title="__('waterhole::admin.dashboard-title')">
    <div class="stack gap-lg">
        @section('debug')
            <x-waterhole::alert type="attention" class="alert--lg" icon="tabler-bug">
                {{ __('waterhole::admin.debug-mode-on-message') }}
                &nbsp;
                <a
                    href="https://waterhole.dev/docs/configuration#debug-mode"
                    class="color-inherit weight-bold nowrap"
                    target="_blank"
                >
                    {{ __('waterhole::system.learn-more-link') }}
                </a>
            </x-waterhole::alert>
        @endsection

        @section('mail')
            <x-waterhole::alert type="attention" class="alert--lg" icon="tabler-mail">
                {{ __('waterhole::admin.configure-mail-message') }}
                &nbsp;
                <a
                    href="https://waterhole.dev/docs/configuration#mail-configuration"
                    class="color-inherit weight-bold nowrap"
                    target="_blank"
                >
                    {{ __('waterhole::system.learn-more-link') }}
                </a>
            </x-waterhole::alert>
        @endsection

        @if ($alerts = Waterhole\Extend\AdminAlerts::build())
            <div class="stack gap-sm">
                @components($alerts)
            </div>
        @endif

        <div class="admin-dashboard">
            @foreach (config('waterhole.admin.widgets', []) as $id => $widget)
                <div style="
                    --admin-dashboard-widget-width: {{ $widget['width'] ?: 100 }}%;
                    @isset($widget['height'])
                        --admin-dashboard-widget-height: {{ $widget['height'] . (is_numeric($widget['height']) ? 'px' : '') }}
                    @endisset
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
                            <x-waterhole::spinner class="spinner--block"/>
                        </turbo-frame>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-waterhole::admin>
