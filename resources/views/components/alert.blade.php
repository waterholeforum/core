<div {{ $attributes
    ->merge(['role' => 'alert'])
    ->class(['alert', $type ? "bg-$type" : null]) }}>
    @if ($icon)
        <div class="alert__icon">
            <x-waterhole::icon :icon="$icon"/>
        </div>
    @endif

    <div class="alert__message">
        {{ $slot }}
    </div>

    @if (! empty($action) || $dismissible)
        <div class="alert__actions">
            {{ $action ?? '' }}

            @if ($dismissible)
                <button
                    class="btn btn--transparent btn--icon"
                    data-action="alerts#dismiss"
                    type="button"
                >
                    <x-waterhole::icon icon="heroicon-s-x"/>
                </button>
            @endif
        </div>
    @endif
</div>
