@inject('license', Waterhole\Licensing\LicenseManager::class)

<div class="cp__version text-xs row gap-xs mt-lg">
    <a href="https://waterhole.dev" class="color-muted" target="_blank">
        Waterhole {{ Waterhole::VERSION }}
    </a>

    @if ($license->valid())
        <a
            href="https://waterhole.dev/account/sites/{{ config('waterhole.system.site_key') }}"
            target="_blank"
            class="badge"
        >
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
    @endif
</div>
