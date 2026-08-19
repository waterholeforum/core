@inject('license', Waterhole\Licensing\LicenseManager::class)

<x-waterhole::cp-layout :title="__('waterhole::cp.dashboard-title')">
    <div class="cp-dashboard stack gap-lg">
        @if ($license->invalid() && $license->production())
            <x-waterhole::alert
                type="danger"
                data-key="license"
                data-duration="-1"
            >
                {{
                    $license->status() === 200
                        ? __([
                            'waterhole::cp.license-' . Str::kebab($license->error()) . '-message',
                            'waterhole::cp.license-invalid-message',
                        ])
                        : __('waterhole::cp.license-error-message', ['status' => $license->status()])
                }}
                <a
                    href="https://waterhole.dev/docs/licensing"
                    target="_blank"
                    class="color-inherit nowrap weight-bold"
                >
                    {{ __('waterhole::system.learn-more-link') }}
                </a>
            </x-waterhole::alert>
        @endif

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

        @if ($alerts = resolve(Waterhole\Extend\Ui\CpAlerts::class)->components())
            <div class="stack gap-sm">
                @components($alerts)
            </div>
        @endif

        <div class="cp-dashboard__widgets">
            @foreach (config('waterhole.cp.widgets', []) as $id => $widget)
                @php
                    $component = $widget['component'];
                    $lazy = $component::$lazy ?? false;
                    $style = [];

                    if (isset($widget['width'])) {
                        $width = $widget['width'];
                        $style[] = '--cp-dashboard-widget-width: ' . (is_numeric($width) ? $width * 100 . '%' : $width);
                    }

                    if (isset($widget['height'])) {
                        $height = $widget['height'];
                        $style[] = '--cp-dashboard-widget-height: ' . $height . (is_numeric($height) ? 'px' : '');
                    }
                @endphp

                <div style="{{ implode('; ', $style) }}">
                    @unless ($lazy)
                        @include('waterhole::cp.widget')
                    @else
                        <turbo-frame
                            id="widget_{{ $id }}"
                            src="{{ route('waterhole.cp.dashboard.widget', compact('id')) }}"
                            data-controller="turbo-frame"
                            data-action="turbo:frame-load->turbo-frame#removeSrc"
                            class="busy-spinner"
                        ></turbo-frame>
                    @endunless
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
</x-waterhole::cp-layout>
