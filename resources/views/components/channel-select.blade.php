<ui-popup placement="bottom-start" {{ $attributes }}>
    <button class="btn" type="button">
        @if ($channel)
            <x-waterhole::channel-label :$channel />
        @else
            {{ __('waterhole::forum.channel-picker-placeholder') }}
        @endif
        @icon('tabler-selector')
    </button>

    <ui-menu class="menu menu--lg" hidden>
        <x-waterhole::channel-picker
            :$name
            :value="$channel?->id"
            :$exclude
            :$showLinks
            :$form
        />
    </ui-menu>
</ui-popup>
