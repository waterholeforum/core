@inject('license', Waterhole\Licensing\LicenseManager::class)

<turbo-frame id="license">
    @if ($license->error())
        <turbo-stream target="alerts" action="append">
            <template>
                <x-waterhole::alert type="danger">
                    {{ __('waterhole::admin.license-error-message') }}
                </x-waterhole::alert>
            </template>
        </turbo-stream>

    @elseif ($license->valid())
        <a href="https://waterhole.dev/account/sites/{{ config('waterhole.system.site_key') }}" target="_blank" class="badge">
            {{ __('waterhole::admin.licensed-badge') }}
        </a>

    @elseif ($license->test())
        <a href="https://waterhole.dev/docs/licensing" target="_blank" class="badge bg-attention">
            {{ __('waterhole::admin.trial-badge') }}
        </a>

    @else
        <a href="https://waterhole.dev/docs/licensing" target="_blank" class="badge bg-danger">
            {{ __('waterhole::admin.unlicensed-badge') }}
        </a>

        <turbo-stream target="alerts" action="append">
            <template>
                <x-waterhole::alert type="danger" data-key="license" data-duration="-1">
                    {{ __('waterhole::admin.license-invalid-message') }} &nbsp;
                    <a href="https://waterhole.dev/docs/licensing" target="_blank" class="link color-inherit nowrap weight-bold">
                        {{ __('waterhole::system.learn-more-link') }}
                    </a>
                </x-waterhole::alert>
            </template>
        </turbo-stream>
    @endif
</turbo-frame>
