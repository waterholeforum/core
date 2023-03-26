@inject('license', Waterhole\Licensing\LicenseManager::class)

<div class="cp__version text-xs row gap-xs" style="margin-top: var(--space-md)">
    <a
        href="https://waterhole.dev"
        class="color-muted"
        target="_blank"
    >Waterhole {{ Waterhole::VERSION }}</a>

    @if ($license->valid())
        <a href="https://waterhole.dev/account/sites/{{ config('waterhole.system.site_key') }}" target="_blank" class="badge">
            {{ __('waterhole::cp.licensed-badge') }}
        </a>

    @elseif ($license->test())
        <a href="https://waterhole.dev/docs/licensing" target="_blank" class="badge bg-warning">
            {{ __('waterhole::cp.trial-badge') }}
        </a>

    @else
        <a href="https://waterhole.dev/docs/licensing" target="_blank" class="badge bg-danger">
            {{ __('waterhole::cp.unlicensed-badge') }}
        </a>

        <turbo-stream target="alerts" action="append">
            <template>
                <x-waterhole::alert type="danger" data-key="license" data-duration="-1">
                    {{ $license->status() === 200
                        ? __([
                              'waterhole::cp.license-'.Str::kebab($license->error()).'-message',
                              'waterhole::cp.license-invalid-message',
                          ])
                        : __('waterhole::cp.license-error-message', ['status' => $license->status()]) }} &nbsp;
                    <a href="https://waterhole.dev/docs/licensing" target="_blank" class="color-inherit nowrap weight-bold">
                        {{ __('waterhole::system.learn-more-link') }}
                    </a>
                </x-waterhole::alert>
            </template>
        </turbo-stream>
    @endif
</div>
