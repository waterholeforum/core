<div
    {{
        $attributes
            ->merge(['role' => 'alert', 'data-controller' => 'alert'])
            ->class(['alert', $type ? "alert--$type bg-$type" : null])
    }}
>
    @if ($icon)
        <div class="alert__icon">
            @icon($icon)
        </div>
    @endif

    <div class="alert__message content">
        {{ $message ?? $slot }}
    </div>

    @if (! empty($action) || $dismissible)
        <div class="alert__actions">
            {{ $action ?? '' }}

            @if ($dismissible)
                <button
                    class="btn btn--transparent btn--icon"
                    data-action="alert#dismiss"
                    type="button"
                >
                    @icon('tabler-x')
                </button>
            @endif
        </div>
    @endif
</div>
