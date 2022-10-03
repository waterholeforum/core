@inject('license', Waterhole\Licensing\LicenseManager::class)

<turbo-frame id="license">
    @if ($license->error())
        <div id="alerts-append" hidden>
            <x-waterhole::alert type="danger">
                {{ __('waterhole::admin.license-error-message') }}
            </x-waterhole::alert>
        </div>

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

        <div id="alerts-append" hidden>
            <x-waterhole::alert type="danger" data-duration="-1" data-key="license">
                {{ __('waterhole::admin.license-invalid-message') }}
                <x-slot:action>
                    <a href="https://waterhole.dev/docs/licensing" target="_blank" class="link nowrap weight-bold">
                        {{ __('waterhole::system.learn-more-link') }}
                    </a>
                </x-slot:action>
            </x-waterhole::alert>
        </div>
    @endif
</turbo-frame>
