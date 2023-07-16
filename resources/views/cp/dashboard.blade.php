<x-waterhole::cp :title="__('waterhole::cp.dashboard-title')">
    <div class="cp-dashboard stack gap-lg">
        @section('debug')
            <x-waterhole::alert type="warning" icon="tabler-bug">
                {{ __('waterhole::cp.debug-mode-on-message') }}
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
            <x-waterhole::alert type="warning" icon="tabler-mail">
                {{ __('waterhole::cp.configure-mail-message') }}
                <a
                    href="https://waterhole.dev/docs/configuration#mail-configuration"
                    class="color-inherit weight-bold nowrap"
                    target="_blank"
                >
                    {{ __('waterhole::system.learn-more-link') }}
                </a>
            </x-waterhole::alert>
        @endsection

        @if ($alerts = Waterhole\Extend\CpAlerts::build())
            <div class="stack gap-sm">
                @components($alerts)
            </div>
        @endif

        <div class="cp-dashboard__widgets">
            @foreach (config('waterhole.cp.widgets', []) as $id => $widget)
                <div
                    style="
                        @isset($widget['width'])
                            --cp-dashboard-widget-width: {{ is_numeric($widget['width']) ? $widget['width'] * 100 . '%' : $widget['width'] }};
                        @endisset
                        @isset($widget['height'])
                            --cp-dashboard-widget-height: {{ $widget['height'] . (is_numeric($widget['height']) ? 'px' : '') }};
                        @endisset
                    "
                >
                    @empty($widget['component']::$lazy)
                        @include('waterhole::cp.widget')
                    @else
                        <turbo-frame
                            id="widget_{{ $id }}"
                            src="{{ route('waterhole.cp.dashboard.widget', compact('id')) }}"
                            data-controller="turbo-frame"
                            data-action="turbo:frame-load->turbo-frame#removeSrc"
                            class="busy-spinner"
                        ></turbo-frame>
                    @endempty
                </div>
            @endforeach
        </div>
    </div>

    <div class="cp-help">
        <a
            href="https://waterhole.dev/docs/dashboard"
            target="_blank"
            class="color-muted with-icon"
        >
            @icon('tabler-help')
            Customize the Dashboard
        </a>
    </div>
</x-waterhole::cp>
