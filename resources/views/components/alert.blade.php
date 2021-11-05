<div {{ $attributes->class(['alert', $type ? "alert--$type" : null]) }}>
    @if ($icon)
        <div class="alert__icon">
            <x-waterhole::icon :icon="$icon"/>
        </div>
    @endif
    <div class="alert__message content">
        {{ $slot }}
    </div>
    <div class="alert__actions">
        {{ $action ?? '' }}

        @if ($dismissible)
            <button type="button" class="btn btn--transparent btn--icon" data-action="alerts#dismiss">
                <x-waterhole::icon icon="heroicon-s-x"/>
            </button>
        @endif
    </div>
</div>
